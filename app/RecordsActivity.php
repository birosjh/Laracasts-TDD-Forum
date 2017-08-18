<?php

namespace App;

trait RecordsActivity
{
    // because it is named boot[TraitName], it will be activated the same way an Eloquent model would be
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;

        // For each activity that should be recorded
        // When that activity's event is fired, list it in the activities table
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

    }

    // Activities of the model that should be recorded in the Activties table
    public static function getActivitiesToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);

        // Can be abbreviated if you use the code above after creating the eloquent relation
        // Activity::create([
        //     'user_id' => auth()->id(),
        //     'type' => $this->getActivityType($event),
        //     'subject_id' => $this->id,
        //     'subject_type' => get_class($this)
        // ]);
    }

    public function activity()
    {
        // morphMany means you are not hardcoding the related model
        // Don't forget to pass in naming convention for subjects
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

}
