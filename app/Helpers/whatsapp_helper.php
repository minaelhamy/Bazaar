<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\User;
use App\Models\WhatsappMessage;
class whatsapp_helper
{
    public static function whatsapp_message_config($vendor_id)
    {
        if (empty($vendor_id)) {
            $whatsappp = WhatsappMessage::first();
        } else {
            $whatsappp = WhatsappMessage::where('vendor_id', $vendor_id)->first();
        }
        return $whatsappp;
    }

    public static function whatsappmessage($order_number, $vdata, $vendordata)
    {
        try {
            $pagee[] = "";
            $getorder = Order::where('order_number', $order_number)->where('vendor_id', $vdata)->first();

            if ($getorder->payment_status == "1") {
                $payment_status = trans('labels.unpaid');
            }
            if ($getorder->payment_status == "2") {
                $payment_status = trans('labels.paid');
            }

            if ($getorder->delivery_charge > 0) {
                $delivery_charge = helper::currency_formate($getorder->delivery_charge, $vdata);
            } else {
                $delivery_charge = trans('labels.free');
            }

            $data = OrderDetails::where('order_id', $getorder->id)->get();
            foreach ($data as $value) {
                if ($value['variants_id'] != "") {
                    $variantsdata = '(' . $value['variants_name'] . ')';
                } else {
                    $variantsdata = "";
                }
                $item_message = whatsapp_helper::whatsapp_message_config($vdata)->item_message;
                $itemvar = ["{qty}", "{item_name}", "{variantsdata}", "{item_price}", "{total}"];
                $newitemvar = [$value['qty'], urlencode($value['item_name']), $variantsdata, helper::currency_formate($value->price, $vdata), helper::currency_formate($value->price * $value['qty'], $vdata)];
                $pagee[] = str_replace($itemvar, $newitemvar, $item_message);

                $extras_id = explode("|", $value['extras_id']);
                $extras_name = explode("|", $value['extras_name']);
                $extras_price = explode("|", $value['extras_price']);
                if ($value['extras_id'] != "") {
                    foreach ($extras_id as $key =>  $addons) {
                        $pagee[] .= "       👉" . $extras_name[$key] . ':' . helper::currency_formate($extras_price[$key], $vdata);
                    }
                }
            }

            $items = implode("|", $pagee);
            $itemlist = str_replace('|', '%0a', $items);

            $tax_amount = explode("|", $getorder->tax);
            $tax_name = explode("|", $getorder->tax_name);

            $tax_data[] = "";
            if ($tax_amount != "") {
                foreach ($tax_amount as $key => $tax_value) {
                    @$tax_data[] .= "👉 " . $tax_name[$key] . ' : ' . helper::currency_formate((float)$tax_amount[$key], $vdata);
                }
            }
            $tdata = implode("|", $tax_data);
            $tax_val = str_replace('|', '%0a', $tdata);

            if ($getorder->order_type == 1) {
                $order_type = trans('labels.delivery');
            } else {
                $order_type = trans('labels.pickup');
            }

            $var = ["{delivery_type}", "{order_no}", "{payment_status}", "{item_variable}", "{tips}", "{sub_total}", "{total_tax}", "{delivery_charge}", "{discount_amount}", "{grand_total}", "{notes}", "{customer_name}", "{customer_mobile}", "{customer_email}", "{address}", "{building}", "{landmark}", "{postal_code}", "{date}", "{time}", "{payment_type}", "{store_name}", "{track_order_url}", "{store_url}"];
            $newvar = [$order_type, $order_number, $payment_status, $itemlist, @helper::currency_formate($getorder->tips, $vdata), @helper::currency_formate($getorder->sub_total, $vdata), $tax_val, $delivery_charge, @helper::currency_formate($getorder->discount_amount, $vdata), helper::currency_formate($getorder->grand_total, $vdata), $getorder->order_notes, $getorder->customer_name, $getorder->mobile, $getorder->customer_email, $getorder->address, $getorder->building, $getorder->landmark, $getorder->pincode, $getorder->delivery_date, $getorder->delivery_time, @helper::getpayment($getorder->payment_type, $vdata)->payment_name, $vendordata->name, helper::storefront_url($vendordata, '/find-order?order=' . $order_number), helper::storefront_url($vendordata)];
            $whmessage = str_replace($var, $newvar, str_replace("\n", "%0a", whatsapp_helper::whatsapp_message_config($vdata)->order_whatsapp_message));
            if (whatsapp_helper::whatsapp_message_config($vdata)->message_type == 1) {
                $whmessage = str_replace($var, $newvar, str_replace("\n", "%0a", @whatsapp_helper::whatsapp_message_config($vdata)->order_whatsapp_message));
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://graph.facebook.com/v18.0/' . whatsapp_helper::whatsapp_message_config($vdata)->whatsapp_phone_number_id . '/messages',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                    "messaging_product": "whatsapp",
                    "to": "917016428845",
                    "text": {
                        "body" : "' . $whmessage . '"
                    }
                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . whatsapp_helper::whatsapp_message_config($vdata)->whatsapp_access_token . ''
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
            }

            return $whmessage;
        } catch (\Throwable $th) {
        }
    }

    public static function orderstatusupdatemessage($order_number, $status, $vendor_id)
    {
        $getorder = Order::where('order_number', $order_number)->where('vendor_id', $vendor_id)->first();
        $vendordata = User::where('id', $vendor_id)->first();
        $var = ["{order_no}", "{customer_name}", "{track_order_url}", "{status}"];
        $newvar = [$order_number, $getorder->user_name, helper::storefront_url($vendordata, '/find-order?order=' . $order_number), $status];
        $whmessage = str_replace($var, $newvar, str_replace("\r\n", "%0a", @whatsapp_helper::whatsapp_message_config($vendor_id)->order_status_message));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v18.0/' . whatsapp_helper::whatsapp_message_config($vendor_id)->whatsapp_phone_number_id . '/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
              "messaging_product": "whatsapp",
              "to": "917016428845",
              "text": {
                  "body" : "' . $whmessage . '"
              }
          }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . whatsapp_helper::whatsapp_message_config($vendor_id)->whatsapp_access_token . ''
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $whmessage;
    }
}
