<?php

namespace PanierfoyenBundle\Listener;

use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use PanierfoyenBundle\Entity\CalendarEvent as Event;

class LoadDataListener {

    /**
     * @param CalendarEvent $calendarEvent
     *
     * @return EventInterface[]
     */
    public function loadData(CalendarEvent $calendarEvent) {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        $filters = $calendarEvent->getFilters();

        //You may want do a custom query to populate the events
        $start = new \DateTime('2016-08-08T17:30');
        $end = new \DateTime('2016-08-08T19:30');

        $event = new Event('Event Title 1', $start);
        $event->setEndDate($end);

        $calendarEvent->addEvent($event);
    }

}
