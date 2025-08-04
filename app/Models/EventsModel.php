<?php

namespace App\Models;

use CodeIgniter\Model;

class EventsModel extends Model
{
    public function getEventsWithDetails()
    {
        $db = \Config\Database::connect();
        return $db->table('events e')
            ->select('
                e.*,
                p.name as project_name,
                up_creator.full_name as created_by_name,
                GROUP_CONCAT(
                    up_attendee.full_name 
                    SEPARATOR ", "
                ) as attendees_names
            ')
            ->join('projects p', 'p.id = e.project_id', 'left')
            ->join('user_profile up_creator', 'up_creator.user_id = e.created_by', 'left')
            ->join('event_attendees ea', 'ea.event_id = e.id AND ea.is_delete = 0 AND ea.is_active = 1', 'left')
            ->join('user_profile up_attendee', 'up_attendee.user_id = ea.user_id', 'left')
            ->where('e.is_delete', 0)
            ->where('e.is_active', 1)
            ->groupBy('e.id')
            ->orderBy('e.start_datetime', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getEventWithDetails($id)
    {
        $db = \Config\Database::connect();
        return $db->table('events e')
            ->select('
                e.*,
                p.name as project_name,
                up_creator.full_name as created_by_name
            ')
            ->join('projects p', 'p.id = e.project_id', 'left')
            ->join('user_profile up_creator', 'up_creator.user_id = e.created_by', 'left')
            ->where('e.id', $id)
            ->where('e.is_delete', 0)
            ->where('e.is_active', 1)
            ->get()
            ->getRowArray();
    }

    public function createEvent($data)
    {
        $db = \Config\Database::connect();
        $data['is_active'] = 1;
        $data['is_delete'] = 0;
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        
        $db->table('events')->insert($data);
        return $db->insertID();
    }

    public function updateEvent($id, $data)
    {
        $db = \Config\Database::connect();
        $data['date_modified'] = date('Y-m-d H:i:s');
        
        return $db->table('events')
            ->where('id', $id)
            ->update($data);
    }

    public function deleteEvent($id)
    {
        $db = \Config\Database::connect();
        return $db->table('events')
            ->where('id', $id)
            ->update([
                'is_delete' => 1,
                'is_active' => 0,
                'date_modified' => date('Y-m-d H:i:s')
            ]);
    }

    public function assignAttendees($eventId, $userIds)
    {
        $db = \Config\Database::connect();
        
        foreach ($userIds as $userId) {
            $db->table('event_attendees')->insert([
                'event_id' => $eventId,
                'user_id' => $userId,
                'status' => 'invited',
                'is_active' => 1,
                'is_delete' => 0,
                'date_created' => date('Y-m-d H:i:s'),
                'date_modified' => date('Y-m-d H:i:s')
            ]);
        }
        return true;
    }

    public function updateAttendees($eventId, $userIds)
    {
        $db = \Config\Database::connect();
        
        // Soft delete existing attendees (set is_active = 0 and is_delete = 1)
        $db->table('event_attendees')
           ->where('event_id', $eventId)
           ->update([
               'is_active' => 0,
               'is_delete' => 1,
               'date_modified' => date('Y-m-d H:i:s')
           ]);

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
                  ->where('ea.is_active', 1)
                  ->get()
                  ->getResultArray();
    }

    public function getEventsForCalendar()
    {
        $db = \Config\Database::connect();
        $events = $db->table('events e')
            ->select('
                e.id,
                e.title,
                e.description,
                e.event_type,
                e.start_datetime as start,
                e.end_datetime as end,
                e.location,
                p.name as project_name
            ')
            ->join('projects p', 'p.id = e.project_id', 'left')
            ->where('e.is_delete', 0)
            ->where('e.is_active', 1)
            ->get()
            ->getResultArray();

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
        $db = \Config\Database::connect();
        return $db->table('events e')
            ->select('
                e.*,
                p.name as project_name,
                up.full_name as created_by_name
            ')
            ->join('projects p', 'p.id = e.project_id', 'left')
            ->join('user_profile up', 'up.user_id = e.created_by', 'left')
            ->where('e.is_delete', 0)
            ->where('e.is_active', 1)
            ->where('e.start_datetime >=', date('Y-m-d H:i:s'))
            ->orderBy('e.start_datetime', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
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
        $db = \Config\Database::connect();
        $stats = [];
        
        // Total events this month
        $stats['total_this_month'] = $db->table('events')
            ->where('is_delete', 0)
            ->where('DATE_FORMAT(start_datetime, "%Y-%m")', date('Y-m'))
            ->countAllResults();

        // Upcoming events (next 7 days)
        $stats['upcoming_week'] = $db->table('events')
            ->where('is_delete', 0)
            ->where('start_datetime >=', date('Y-m-d H:i:s'))
            ->where('start_datetime <=', date('Y-m-d H:i:s', strtotime('+7 days')))
            ->countAllResults();

        // Events by type
        $stats['by_type'] = $db->table('events')
            ->select('event_type, COUNT(*) as count')
            ->where('is_delete', 0)
            ->where('DATE_FORMAT(start_datetime, "%Y-%m")', date('Y-m'))
            ->groupBy('event_type')
            ->get()
            ->getResultArray();

        return $stats;
    }
}
