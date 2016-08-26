<?php

namespace PanierfoyenBundle\Listener;

use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use PanierfoyenBundle\Entity\CalendarEvent as Event;
use AncaRebeca\FullCalendarBundle\Model\Event as BaseEvent;

class LoadDataListener {

    /**
     * @param CalendarEvent $calendarEvent
     *
     * @return EventInterface[]
     */
    public function loadData(CalendarEvent $calendarEvent) {
        $startDate = $calendarEvent->getStart();
        $endDate = $calendarEvent->getEnd();
        $filters = $calendarEvent->getFilters();

        //You may want do a custom query to populate the events
        $start = new \DateTime('2016-08-26T17:30:00');
//        $start = new \DateTime();
        $end = new \DateTime('2016-08-26T19:30:00');

        $event = new Event('Distribution salle blabla', $start);
        $event->setEndDate($end);
//        $event->setAllDay(false);

        $calendarEvent->addEvent($event);
    }

}
