<?php

namespace App\Http\Requests\ProfileMessage;

use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;

class SendProfileMessageRequest extends FormRequest
{
    public const ID_FROM = 'id_from';
    public const ID_TO = 'id_to';
    public const MESSAGE_TEXT = 'message_text';

    public function authorize(): bool
    {
       $profileId = Profile::query()
           ->where('user_id', $this->getIdFrom())
           ->firstOrFail()->id;

        return $this->user()->can('hasAccess', [Profile::class, $profileId]);
    }

    public function rules(): array
    {
        return [
            self::ID_FROM => [
                'int',
                'exists:users,id',
            ],

            self::ID_TO => [
                'exists:users,id',
                'different:' . self::ID_FROM,
            ],

            self::MESSAGE_TEXT => [
                'string',
                'required',
            ],
        ];
    }

    public function getIdFrom(): int
    {
        return $this->input(self::ID_FROM);
    }

    public function getIdTo(): int
    {
        return $this->input(self::ID_TO);
    }

    public function getMessageText(): string
    {
        return $this->get(self::MESSAGE_TEXT);
    }
}
