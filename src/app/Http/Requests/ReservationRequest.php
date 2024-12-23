<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Shop;
use Carbon\Carbon;


class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');
        $shop = $this->getShop();

        $maxPeople = $shop ? $shop->number : 10; // デフォルトを10人とする

        return [
            'shop_id' => ['required', 'exists:shops,id'],
            'date' => ['required', 'date', 'after_or_equal:' . $today,
            function ($attribute, $value, $fail) {
                    $shop = $this->getShop();
                    if ($shop && is_array($shop->regular_holidays) && in_array(Carbon::parse($value)->format('l'), $shop->regular_holidays)) {
                        $fail('選択された日は定休日のため予約できません。');
                    }
                },
            ],
            'time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($currentTime, $today) {
                    $selectedDate = $this->input('date');
                    $shop = $this->getShop();
                    if (!$shop) {
                        $fail('指定された店舗が存在しません。');
                        return;
                    }
                    if ($selectedDate === $today && $value < $currentTime) {
                        $fail('選択された時間は現在時刻以降である必要があります。');
                        return;
                    }
                    if ($value < $shop->open_hours || $value > $shop->close_hours) {
                        $fail('選択された時間は予約可能時間外です。');
                    }
                },
            ],
            'number' => ['required', 'integer', 'min:1', 'max:' . $maxPeople],
        ];
    }

    public function messages()
    {
        $shop = $this->getShop();
        $maxPeople = $shop ? $shop->max_people : 10;

        return [
            'shop_id.required' => '店舗IDは必須です。',
            'shop_id.exists' => '指定された店舗が見つかりません。',
            'date.after_or_equal' => '日付は本日以降を選択してください。',
            'time.date_format' => '正しい時間形式を選択してください。',
            'number.min' => '予約人数は最低1人以上を指定してください。',
            'number.max' => '予約人数は最大'. $maxPeople .'人までです。',
            'date.custom_regular_holiday' => '選択された日は定休日のため予約できません。',
            'date.custom_closed_day' => '選択された日は休業日のため予約できません。',
            'time.custom_time_out_of_range' => '選択された時間は予約可能時間外です。',
        ];
    }

    private $shop = null;

    private function getShop()
    {
        if ($this->shop === null) {
            $this->shop = Shop::find($this->input('shop_id'));
        }
        return $this->shop;
    }

}
