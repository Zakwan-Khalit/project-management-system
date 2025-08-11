<?php

namespace App\Controllers;

use App\Models\EventsModel;
use App\Models\ProjectModel;
use App\Models\UserModel;

class Events extends BaseController
{
    protected $eventsModel;
    protected $projectsModel;
    protected $usersModel;
        protected $activityLog;

    public function __construct()
    {
        $this->eventsModel = new EventsModel();
        $this->projectsModel = new ProjectModel();
        $this->usersModel = new UserModel();
        $this->activityLog = new \App\Models\ActivityLogModel();
    }

    /**
     * Check if user has admin or manager role (role 1 or 2)
     */
    private function hasEditPermission()
    {
        $userData = session('userdata');
        $roleId = $userData['role_id'] ?? null;
        return in_array($roleId, [1, 2]);
    }

    public function index()
    {
        try {
            $data = [
                'title' => 'Events & Schedule',
                'events' => $this->eventsModel->getEventsWithDetails(),
                'projects' => $this->projectsModel->getAllProjects(),
                'users' => $this->usersModel->getAllUsers()
            ];

            return $this->template->member('events/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Events index error: ' . $e->getMessage());
            $data = [
                'title' => 'Events & Schedule',
                'error_message' => 'Unable to load events data. Please try again later.',
                'events' => [],
                'projects' => [],
                'users' => []
            ];
            return $this->template->member('events/index', $data);
        }
    }

    public function create()
    {
        // Check if user has permission to create events
        if (!$this->hasEditPermission()) {
            return redirect()->to('/events')->with('error', 'You do not have permission to create events.');
        }

        if ($this->request->getMethod() === 'POST') {
            return $this->store();
        }

        try {
            $data = [
                'title' => 'Create Event',
                'projects' => $this->projectsModel->getAllProjects(),
                'users' => $this->usersModel->getAllUsers()
            ];

            return $this->template->member('events/create', $data);
        } catch (\Exception $e) {
            log_message('error', 'Events create error: ' . $e->getMessage());
            return redirect()->to('/events')->with('error', 'Unable to load event creation form.');
        }
    }

    public function store()
    {
        // Check if user has permission to create events
        if (!$this->hasEditPermission()) {
            return redirect()->to('/events')->with('error', 'You do not have permission to create events.');
        }
            $this->activityLog->logActivity([
                'user_id' => session('userdata')['id'] ?? null,
                'action' => 'create_event',
                'description' => 'Created a new event',
                'details' => json_encode($this->request->getPost()),
            ]);

        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'event_type' => 'required|in_list[meeting,deadline,milestone,training,review,other]',
            'start_datetime' => 'required|valid_date',
            'end_datetime' => 'required|valid_date',
            'location' => 'permit_empty|max_length[255]',
            'project_id' => 'permit_empty|is_natural',
            'attendees' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            $eventData = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'event_type' => $this->request->getPost('event_type'),
                'start_datetime' => $this->request->getPost('start_datetime'),
                'end_datetime' => $this->request->getPost('end_datetime'),
                'location' => $this->request->getPost('location'),
                'project_id' => $this->request->getPost('project_id') ?: null,
                'created_by' => session('user_id')
            ];

            $eventId = $this->eventsModel->createEvent($eventData);

            // Handle attendees
            $attendees = $this->request->getPost('attendees');
            if ($attendees && is_array($attendees)) {
                $this->eventsModel->assignAttendees($eventId, $attendees);
            }

            return redirect()->to('/events')->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            log_message('error', 'Event creation error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create event. Please try again.');
        }
    }

    public function edit($id)
    {
        // Check if user has permission to edit events
        if (!$this->hasEditPermission()) {
            return redirect()->to('/events')->with('error', 'You do not have permission to edit events.');
        }

        if ($this->request->getMethod() === 'POST') {
            return $this->update($id);
        }

        try {
            $event = $this->eventsModel->getEventWithDetails($id);
            if (!$event) {
                return redirect()->to('/events')->with('error', 'Event not found.');
            }

            $data = [
                'title' => 'Edit Event',
                'event' => $event,
                'projects' => $this->projectsModel->getAllProjects(),
                'users' => $this->usersModel->getAllUsers(),
                'attendees' => $this->eventsModel->getEventAttendees($id)
            ];

            return $this->template->member('events/edit', $data);
        } catch (\Exception $e) {
            log_message('error', 'Events edit error: ' . $e->getMessage());
            return redirect()->to('/events')->with('error', 'Unable to load event for editing.');
        }
    }

    public function update($id)
    {
        // Check if user has permission to update events
        if (!$this->hasEditPermission()) {
            return redirect()->to('/events')->with('error', 'You do not have permission to update events.');
        }
            $this->activityLog->logActivity([
                'user_id' => session('userdata')['id'] ?? null,
                'action' => 'update_event',
                'description' => 'Updated event',
                'details' => json_encode($this->request->getPost()),
            ]);

        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'event_type' => 'required|in_list[meeting,deadline,milestone,training,review,other]',
            'start_datetime' => 'required|valid_date',
            'end_datetime' => 'required|valid_date',
            'location' => 'permit_empty|max_length[255]',
            'project_id' => 'permit_empty|is_natural'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            $eventData = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'event_type' => $this->request->getPost('event_type'),
                'start_datetime' => $this->request->getPost('start_datetime'),
                'end_datetime' => $this->request->getPost('end_datetime'),
                'location' => $this->request->getPost('location'),
                'project_id' => $this->request->getPost('project_id') ?: null
            ];

            $this->eventsModel->updateEvent($id, $eventData);

            // Update attendees
            $attendees = $this->request->getPost('attendees');
            $this->eventsModel->updateAttendees($id, $attendees ?: []);

            // Check if this is an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Event updated successfully!'
                ]);
            }

            return redirect()->to('/events')->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            log_message('error', 'Event update error: ' . $e->getMessage());
            
            // Check if this is an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to update event. Please try again.'
                ]);
            }
            
            return redirect()->back()->withInput()->with('error', 'Failed to update event. Please try again.');
        }
    }

    public function delete($id)
    {
        // Check if user has permission to delete events
        if (!$this->hasEditPermission()) {
            return redirect()->to('/events')->with('error', 'You do not have permission to delete events.');
        }
            $this->activityLog->logActivity([
                'user_id' => session('userdata')['id'] ?? null,
                'action' => 'delete_event',
                'description' => 'Deleted event',
                'details' => json_encode(['event_id' => $id]),
            ]);

        try {
            $this->eventsModel->deleteEvent($id);
            return redirect()->to('/events')->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            log_message('error', 'Event deletion error: ' . $e->getMessage());
            return redirect()->to('/events')->with('error', 'Failed to delete event.');
        }
    }

    public function getCalendarEvents()
    {
        try {
            $events = $this->eventsModel->getEventsForCalendar();
            return $this->response->setJSON($events);
        } catch (\Exception $e) {
            log_message('error', 'Calendar events API error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to load events']);
        }
    }

    public function getProjectUsers($projectId)
    {
        try {
            $users = $this->usersModel->getProjectUsers($projectId);
            return $this->response->setJSON($users);
        } catch (\Exception $e) {
            log_message('error', 'Project users API error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to load project users']);
        }
    }

    public function getDashboardEvents()
    {
        try {
            $upcomingEvents = $this->eventsModel->getUpcomingEvents(5);
            return $this->response->setJSON($upcomingEvents);
        } catch (\Exception $e) {
            log_message('error', 'Dashboard events API error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to load upcoming events']);
        }
    }

    public function getAllUsers()
    {
        try {
            $users = $this->usersModel->getAllUsers();
            return $this->response->setJSON($users);
        } catch (\Exception $e) {
            log_message('error', 'Get all users API error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to load users']);
        }
    }
}
