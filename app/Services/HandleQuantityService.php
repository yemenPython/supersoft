<?php


namespace App\Services;


class HandleQuantityService
{

    public function acceptQuantity($items, $type)
    {
        foreach ($items as $item) {

            if ($type != 'add') {

                $data = $this->checkItemQuantity($item);

                if (!$data['status']) {

                    $message = isset($data['message']) ? $data['message'] : '';
                    return ['status'=> false , 'message'=> $message];
                }
            }

            $this->saveStoreQuantity($item, $type);

            $this->savePartQuantity($item, $type);
        }

        return ['status'=> true];
    }

    public function checkItemQuantity($item)
    {
        $data = ['status' => true];

        $part = $item->part;

        $unitQuantity = $item->partPrice ? $item->partPrice->quantity : 1;

        $requestedQuantity = $unitQuantity * $item->quantity;

        $partStorePivot = $part->stores()->where('store_id', $item->store_id)->first();

        if (!$partStorePivot || !$partStorePivot->pivot) {

            $data['status'] = false;
            $data['message'] = __('store not valid or not related to this part');

            return $data;
        }

        if ($partStorePivot->pivot->quantity < $requestedQuantity) {

            $data['status'] = false;
            $data['message'] = __('Store quantity less than requested ');
        }

        if ($part->quantity < $requestedQuantity) {

            $data['status'] = false;
            $data['message'] = __('Part quantity less than requested');

            return $data;
        }

        return $data;
    }

    public function saveStoreQuantity($item, $type)
    {
        $part = $item->part;

        $partStorePivot = $part->stores()->where('store_id', $item->store_id)->first();

        if (!$partStorePivot) {
            $part->stores()->attach($item->store_id);
        }

        $partStorePivot = $part->stores()->where('store_id', $item->store_id)->first();

        $unitQuantity = $item->partPrice ? $item->partPrice->quantity : 1;

        $requestedQuantity = $unitQuantity * $item->quantity;

        if (!$partStorePivot || !$partStorePivot->pivot) {
            return false;
        }

        if ($type == 'add') {

            $this->increaseQuantity($partStorePivot->pivot, $requestedQuantity);

        } else {

            $this->reduceQuantity($partStorePivot->pivot, $requestedQuantity);
        }

        return true;
    }

    public function savePartQuantity($item, $type)
    {
        $part = $item->part;

        $unitQuantity = $item->partPrice ? $item->partPrice->quantity : 1;

        $requestedQuantity = $unitQuantity * $item->quantity;

        if ($type == 'add') {

            $this->increaseQuantity($part, $requestedQuantity);

        } else {

            $this->reduceQuantity($part, $requestedQuantity);
        }

        return true;
    }

    public function increaseQuantity($model, $quantity)
    {
        $model->quantity += $quantity;

        $model->save();

        return true;
    }

    public function reduceQuantity($model, $quantity)
    {
        $model->quantity -= $quantity;

        if ($model->quantity < 0) {
            $model->quantity = 0;
        }

        $model->save();

        return true;
    }
}
