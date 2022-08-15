<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CustomerManualTransferStatus extends Enum
{
    const StatusNeedConfirmation = 'need_confirmed';
    const StatusAccept = 'accept';
    const StatusReject = 'rejected';
    const StatusRejectReupload = 'rejected_reupload';
    const StatusCustomerReupload = 'customer_reupload';
}
