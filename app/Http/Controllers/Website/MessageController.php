<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Traits\SystemFunctions;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use SystemFunctions;

    /**
     * @throws GuzzleException
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required',
            'topic' => 'required',
            'content' => 'required',
            'recaptcha' => 'required'
        ]);

        if($validator->passes()) {
            $token = $request['recaptcha'];

            if($token) {
                $client = new Client();

                $client->post('https://www.google.com/recaptcha/api/siteverify', [
                    'form_params' => [
                        'secret' => $this->getSecret(),
                        'response' => $token
                    ]
                ]);
            }

            $subject = $request['topic'];
            $request['env'] = $this->getAppEnvironment();

            switch ($subject) {
                case 'events':
                    Mail::send('email.template', [
                        'content' => $request['content'],
                        'number' => $request['contact_number'],
                        'email' => $request['email'],
                        'sender' => $request['name'],
                        'env' => $request['env']
                    ],
                        function ($message) {
                        $message->to('events@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Event Partnerships');
                    });
                    break;
                case 'hosts':
                    Mail::send('email.template', [
                        'content' => $request['content'],
                        'number' => $request['contact_number'],
                        'email' => $request['email'],
                        'sender' => $request['name'],
                        'env' => $request['env']
                    ],
                        function ($message) {
                        $message->to('hosts@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Hosting Requirements');
                    });
                    break;
                case 'internship':
                    Mail::send('email.template', [
                        'content' => $request['content'],
                        'number' => $request['contact_number'],
                        'email' => $request['email'],
                        'sender' => $request['name'],
                        'env' => $request['env']
                    ],
                        function ($message) {
                        $message->to('internship@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Internship');
                    });
                    break;
                case 'jobs':
                    Mail::send('email.template', [
                        'content' => $request['content'],
                        'number' => $request['contact_number'],
                        'email' => $request['email'],
                        'sender' => $request['name'],
                        'env' => $request['env']
                    ],
                        function ($message) {
                        $message->to('jobs@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Job Openings');
                    });
                    break;
                case 'sales':
                    Mail::send('email.template', [
                        'content' => $request['content'],
                        'number' => $request['contact_number'],
                        'email' => $request['email'],
                        'sender' => $request['name'],
                        'env' => $request['env']
                    ],
                        function ($message) {
                        $message->to('sales@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Advertising Inquiries');
                    });
                    break;
                case 'schools':
                    Mail::send('email.template', [
                        'content' => $request['content'],
                        'number' => $request['contact_number'],
                        'email' => $request['email'],
                        'sender' => $request['name'],
                        'env' => $request['env']
                    ],
                        function ($message) {
                        $message->to('schools@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('School Events');
                    });
                    break;
                case 'scholars':
                    Mail::send('email.template', [
                        'content' => $request['content'],
                        'number' => $request['contact_number'],
                        'email' => $request['email'],
                        'sender' => $request['name'],
                        'env' => $request['env']
                    ],
                        function ($message) {
                        $message->to('scholar@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Monster Scholarship Program');
                    });
                    break;
                case 'mobile-app':
                    Mail::send('email.template', [
                        'content' => $request['content'],
                        'number' => $request['contact_number'],
                        'email' => $request['email'],
                        'sender' => $request['name'],
                        'env' => $request['env']
                    ],
                        function ($message) {
                        $message->to('ios@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Monster Mobile App');
                        $message->to('sean@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Monster Mobile App');
                        $message->to('raffyb@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Monster Mobile App');
                        $message->to('raissa@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Monster Mobile App');
                        $message->to('ianna@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Monster Mobile App');
                    });
                    break;
                default:
                    Mail::send('email.template', [
                        'content' => $request['content'],
                        'number' => $request['contact_number'],
                        'email' => $request['email'],
                        'sender' => $request['name'],
                        'env' => $request['env']
                    ], function ($message) {
                        $message->to('info@rx931.com', 'Monster RX93.1')
                            ->from('online@rx931.com')
                            ->subject('Website Message');
                    });
                    break;
            }

            $message = new Message($request->all());
            $message->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Message successfully sent! Thank you for reaching out monster!'
            ], 201);
        } else {
            return $this->json('error', $validator->errors()->all(), 400);
        }
    }
}
