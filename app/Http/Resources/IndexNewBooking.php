<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use App\Models\Bouns;
use App\Models\Child;
use App\Models\User;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexNewBooking extends JsonResource
{


    public function toArray(Request $request): array
    {

        $result = [];
        if ($this != null)
                if ($this != null)
                {
                if (!empty($this['bookingindex'])) {
                    if (!empty($this['bookingindex']['id_center']) &&
                        !$this['bookingindex']['id_center'] == null) {
                        $type = 'center';
                    } else if (!empty($this['bookingindex']['id_online']) &&
                        !$this['bookingindex']['id_online'] == null) {
                        $type = 'online';
                    }
                }

                $result = [
                    'id_booking' => $this['id']?? null,
                    'id_online_center' => $this['id_online_center']?? null,
                    'id_user' => $this['id_user']?? null,
                    'created_at' => date('yy_m_d', strtotime( $this['created_at'])) ?? null,
                    'name' => $this['users']['profile']['name']?? null,
                    'lastName' => $this['users']['profile']['lastName']?? null,
                    'birthDate' => $this['users']['profile']['birthDate']?? null,
                    'mobilePhone' => $this['users']['profile']['mobilePhone']?? null,
                    'specialization' => $this['users']['profile']['specialization']?? null,
                    'levelEducational' => $this['users']['profile']['levelEducational']?? null,
                    'id_profile' => $this['users']['profile']['id']?? null,
                    'id_coursepaper' => $this['bookingindex']['coursepaper'][0]['id']?? null,
                    'id_paper' => $this['bookingindex']['coursepaper'][0]['id_paper']?? null,
                    'namecourse' => $this['bookingindex']['course']['name']?? null,
                    'id_bookingindex' => $this['bookingindex']['id']?? null,
                     'type' => $type
                ];
            }


            return $result;
        }


}
