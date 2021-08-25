<?php

namespace App\Enum;


abstract class Status extends AbstractEnum
{
    const Accepted = "accepted";

    const Rejected = "rejected";

    const Progress = "progress";
}
