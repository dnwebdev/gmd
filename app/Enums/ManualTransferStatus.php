<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ManualTransferStatus extends Enum
{
    const StatusApprove = 'approved';
    const StatusReject = 'rejected';
    const StatusNeedApprovel = 'need_approval';
    const StatusInActive = 'inactive';

    public static function manualList(){
        return [
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'need_approval' => 'Need Approval',
            'inactive' => 'Inactive'
        ];
    }
}
