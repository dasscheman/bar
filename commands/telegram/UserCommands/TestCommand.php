<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\KeyboardButton;

class TestCommand extends UserCommand
{
    protected $name = 'test';                      // Your command's name
    protected $description = 'A command for test'; // Your command description
    protected $usage = '/test';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID

        $btn = Keyboard::button([
            'text' => 'Share my phone number',
            'request_contact' => true
        ]);
        
        $keyboard = Keyboard::make([
            'keyboard' => [[$btn]],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

//        $response = $telegram->replyWithMessage([
//            'text' => 'Blah blah blah',
//            'reply_markup' => $keyboard
//        ]);

        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => 'This is just a Test...' .  echo $btn, // Set message to send
//            'reply_markup' => $keyboard
        ];

        return Request::sendMessage($data);        // Send message!
    }
}