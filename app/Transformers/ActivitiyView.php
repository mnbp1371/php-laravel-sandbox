<?php

namespace App\Transformers;

use App\Activity;
use League\Fractal\TransformerAbstract;

class ActivitiyView extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($activity)
    {


        $result = $this->transform2($activity);

        $stepDate = null;
        if (isset($activity->created_at)) {
            $stepDate = $activity->created_at->format('Y-m-d h-m-s');
        }


        return [


            'id' => $activity['id'],
            'request' => json_encode($result),
            'response' => $activity['response'],
            'order_id' => $activity['order_id'],
            'http_code' => $activity['http_code'],
            'step' => $activity['step'],
            'link' => json_decode($activity['response'])->link,
            'create_at' => $stepDate,


        ];
    }


    public function transform2($activity)
    {


        $params = json_decode($activity['request']);


        $header = [
            'Content-Type' => 'application/json',
            "X-API-KEY" => $params->API_KEY,
            'X-SANDBOX' => (int)$params->sandbox
        ];


        return [

            'url' => 'https://api.idpay.ir/v1.1/payment',
            'header' => $header,
            'params' => $this->params($params)

        ];
    }


    public function params($params)
    {


        return [

            "order_id" => $params->order_id,
            "amount" => $params->amount,
            "name" => $params->name,
            "phone" => $params->phone,
            "mail" => $params->mail,
            "desc" => $params->desc,
            "callback" => $params->callback,
            "status" => $params->status,
            "reseller" => $params->reseller,
        ];
    }

}