<?php

namespace App\Models;

use CodeIgniter\Model;

class EventsModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'title', 'description', 'event_type', 'start_datetime', 'end_datetime',
        'location', 'project_id', 'created_by', 'is_active', 'is_delete'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_created';
    protected $updatedField = 'date_modified';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getEventsWithDetails()
    {
        return $this->select('
                events.*,
                projects.name as project_name,
                up_creator.full_name as created_by_name,
                GROUP_CONCAT(
                    up_attendee.full_name 
                    SEPARATOR ", "
                ) as attendees_names
            ')
            ->join('projects', 'projects.id = events.project_id', 'left')
            ->join('user_profile up_creator', 'up_creator.user_id = events.created_by', 'left')
            ->join('event_attendees ea', 'ea.event_id = events.id', 'left')
            ->join('user_profile up_attendee', 'up_attendee.user_id = ea.user_id', 'left')
            ->where('events.is_delete', 0)
            ->groupBy('events.id')
            ->orderBy('events.start_datetime', 'ASC')
            ->findAll();
    }

    public function getEventWithDetails($id)
    {
        return $this->select('
                events.*,
                projects.name as project_name,
                up_creator.full_name as created_by_name
            ')
            ->join('projects', 'projects.id = events.project_id', 'left')
            ->join('user_profile up_creator', 'up_creator.user_id = events.created_by', 'left')
            ->where('events.id', $id)
            ->where('events.is_delete', 0)
            ->first();
    }

    public function createEvent($data)
    {
        $data['is_active'] = 1;
        $data['is_delete'] = 0;
        $this->insert($data);
        return $this->getInsertID();
    }

    public function updateEvent($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteEvent($id)
    {
        return $this->update($id, ['is_delete' => 1]);
    }

    public function assignAttendees($eventId, $userIds)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('event_attendees');

        foreach ($userIds as $userId) {
            $builder->insert([
                'event_id' => $eventId,
                'user_id' => $userId,
                'status' => 'invited',
                'is_active' => 1,
                'is_delete' => 0
            ]);
        }
        return true;
    }

    public function updateAttendees($eventId, $userIds)
    {
        $db = \Config\Database::connect();
        
        // Remove existing attendees
        $db->table('event_attendees')
           ->where('event_id', $eventId)
           ->update(['is_delete' => 1]);

        // Add new attendees
        if (!empty($userIds)) {
            $this->assignAttendees($eventId, $userIds);
        }
        
        return true;
    }

    public function getEventAttendees($eventId)
    {
        $db = \Config\Database::connect();
        return $db->table('event_attendees ea')
                  ->select('ea.user_id, up.full_name as name, ea.status')
                  ->join('user_profile up', 'up.user_id = ea.user_id')
                  ->where('ea.event_id', $eventId)
                  ->where('ea.is_delete', 0)
                  ->get()
                  ->getResultArray();
    }

    public function getEventsForCalendar()
    {
        $events = $this->select('
                events.id,
                events.title,
                events.description,
                events.event_type,
                events.start_datetime as start,
                events.end_datetime as end,
                events.location,
                projects.name as project_name
            ')
            ->join('projects', 'projects.id = events.project_id', 'left')
            ->where('events.is_delete', 0)
            ->where('events.is_active', 1)
            ->findAll();

        // Format for FullCalendar
        $calendarEvents = [];
        foreach ($events as $event) {
            $color = $this->getEventTypeColor($event['event_type']);
            
            $calendarEvents[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'start' => $event['start'],
                'end' => $event['end'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'description' => $event['description'],
                    'type' => $event['event_type'],
                    'location' => $event['location'],
                    'project' => $event['project_name']
                ]
            ];
        }

        return $calendarEvents;
    }

    public function getUpcomingEvents($limit = 5)
    {
        return $this->select('
                events.*,
                projects.name as project_name,
                up.full_name as created_by_name
            ')
            ->join('projects', 'projects.id = events.project_id', 'left')
            ->join('user_profile up', 'up.user_id = events.created_by', 'left')
            ->where('events.is_delete', 0)
            ->where('events.is_active', 1)
            ->where('events.start_datetime >=', date('Y-m-d H:i:s'))
            ->orderBy('events.start_datetime', 'ASC')
            ->limit($limit)
            ->findAll();
    }

    private function getEventTypeColor($type)
    {
        $colors = [
            'meeting' => '#3788d8',
            'deadline' => '#dc3545',
            'milestone' => '#28a745',
            'training' => '#fd7e14',
            'review' => '#6f42c1',
            'other' => '#6c757d'
        ];

        return $colors[$type] ?? '#6c757d';
    }

    public function getEventStats()
    {
        $stats = [];
        
        // Total events this month
        $stats['total_this_month'] = $this->where('is_delete', 0)
            ->where('DATE_FORMAT(start_datetime, "%Y-%m")', date('Y-m'))
            ->countAllResults();

        // Upcoming events (next 7 days)
        $stats['upcoming_week'] = $this->where('is_delete', 0)
            ->where('start_datetime >=', date('Y-m-d H:i:s'))
            ->where('start_datetime <=', date('Y-m-d H:i:s', strtotime('+7 days')))
            ->countAllResults();

        // Events by type
        $stats['by_type'] = $this->select('event_type, COUNT(*) as count')
            ->where('is_delete', 0)
            ->where('DATE_FORMAT(start_datetime, "%Y-%m")', date('Y-m'))
            ->groupBy('event_type')
            ->findAll();

        return $stats;
    }
}
