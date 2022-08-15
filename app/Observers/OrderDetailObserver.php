<?php

namespace App\Observers;

use App\Models\OrderDetail;
use App\Models\ProductSchedule;

class OrderDetailObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(OrderDetail $orderDetail)
    {
        $schedule = ProductSchedule::select('id_schedule')
            ->where('id_product', $orderDetail->id_product)
            ->where('start_date', '<=', $orderDetail->schedule_date)
            ->where('end_date', '>=',  $orderDetail->schedule_date)
            ->first();

        if (!empty($schedule->id_schedule)) {
            $orderDetail->id_schedule = $schedule->id_schedule;
        }
    }
}
