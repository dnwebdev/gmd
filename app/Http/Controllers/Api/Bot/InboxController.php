<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\Inbox;
use App\Models\MenuBot;
use App\Models\MenuKeyword;
use App\Models\RawInbox;
use App\Models\WoowaContact;
use App\Traits\DiscordTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ZenzivaTrait;
use Kayiz\Woowa;

class InboxController extends Controller
{
    use DiscordTrait,ZenzivaTrait;
    public function index(Request $request)
    {
        RawInbox::create([
            'raw'=>$request->all()
        ]);
        if (env('BOT_WOOWA',true) == false){
            return response()->json("Bot disabled frowm gomodo");
        }
        $mainMenu = MenuBot::where('slug', 'main')->first();
        $whiteListDuration = env('WHITELIST_DURATION', 2);
        $msg = trim(strtolower($request->get('message')));
        if (!$mainMenu):
            return response()->json('no menu');
        endif;
        if (!$request->filled('contact_name')):
            return response()->json('no phone');
        endif;
        $contact = WoowaContact::firstOrCreate(['phone' => $request->get('contact_name')]);
        if ($contact->whitelist_until && $contact->whitelist_until > \Carbon\Carbon::now()->toDateTimeString()) {
            return response()->json("On WhiteList");
        }
        $lastMessage = Inbox::whereHas('contact', function ($c) use ($contact) {
            $c->where('contact_id', $contact->id);
        })->where('created_at', '>', \Carbon\Carbon::now()->subHour()->toDateTimeString())->orderBy('created_at', 'desc')->first();

        if (!$lastMessage):
            $inbox = new Inbox();
            $inbox->menu_id = null;
            $inbox->contact_id = $contact->id;
            $inbox->ip = \request()->get('ip_server');
            $inbox->message = \request()->get('message');
            $inbox->has_reply = 0;
            $inbox->save();

            $woowa = Woowa::SendMessageSync()->setPhone($contact->phone)->setMessage($mainMenu->content)->prepareContent()->send()->getResponse();
            if ($woowa['status'] == true):
                $inbox->has_reply = 1;
                $inbox->save();
            endif;
            return response()->json($mainMenu->content);
        elseif ($lastMessage->has_reply == "0"):
            Woowa::SendMessageSync()->setPhone($contact->phone)->setMessage("Mohon tunggu sebentar ...")->prepareContent()->send()->getResponse();
        else:
            if (!$lastMessage->menu):
                $match = MenuKeyword::whereHas('menu', function ($menu) {
                    $menu->whereHas('parent', function ($parent) {
                        $parent->where('slug', 'main');
                    });
                })->where('keyword', $msg)->first();
                if (!$match):
                    $reply = "mohon masukan pilihan dengan benar\n\n";
                    $reply .= $mainMenu->content;
                    Woowa::SendMessageSync()->setPhone($contact->phone)->setMessage($reply)->prepareContent()->send()->getResponse();
                    return response()->json($reply);
                else:
                    $inbox = new Inbox();
                    $inbox->menu_id = $match->menu->id;
                    $inbox->contact_id = $contact->id;
                    $inbox->ip = \request()->get('ip_server');
                    $inbox->message = \request()->get('message');
                    $inbox->has_reply = 0;
                    $inbox->save();

                    $woowa = Woowa::SendMessageSync()->setPhone($contact->phone)->setMessage($match->menu->content)->prepareContent()->send()->getResponse();
                    if ($woowa['status'] == true):
                        $inbox->has_reply = 1;
                        $inbox->save();
                    endif;
                    if ($match->menu->slug == 'contact-cs'):
                        $contact->update(['whitelist_until' => \Carbon\Carbon::now()->addHours($whiteListDuration)->toDateTimeString()]);
                        $this->sendDiscord($contact->phone);
                    endif;
                    return response()->json($match->menu->content);
                endif;
            else:
                $match = MenuKeyword::whereHas('menu', function ($menu) use ($lastMessage) {
                    $menu->whereHas('parent', function ($parent) use ($lastMessage) {
                        $parent->where('id', $lastMessage->menu_id);
                    });
                })->where('keyword', $msg)->first();
                if (!$match):
                    if ($msg == "99" || $msg == "back" || $msg == "kembali"):
                        $inbox = new Inbox();
                        $inbox->menu_id = $lastMessage->menu->parent ? $lastMessage->menu->parent->id : null;
                        $inbox->contact_id = $contact->id;
                        $inbox->ip = \request()->get('ip_server');
                        $inbox->message = \request()->get('message');
                        $inbox->has_reply = 0;
                        $inbox->save();

                        $woowa = Woowa::SendMessageSync()->setPhone($contact->phone)->setMessage($lastMessage->menu->parent->content)->prepareContent()->send()->getResponse();
                        if ($woowa['status'] == true):
                            $inbox->has_reply = 1;
                            $inbox->save();
                        endif;
                        return response()->json($lastMessage->menu->parent->content);
                    endif;
                    $reply = "Mohon masukan pilihan dengan benar\n\n";
                    $reply .= $lastMessage->menu->content;
                    Woowa::SendMessageSync()->setPhone($contact->phone)->setMessage($reply)->prepareContent()->send()->getResponse();
                    return response()->json($reply);
                else:
                    $inbox = new Inbox();
                    $inbox->menu_id = $match->menu->id;
                    $inbox->contact_id = $contact->id;
                    $inbox->ip = \request()->get('ip_server');
                    $inbox->message = \request()->get('message');
                    $inbox->has_reply = 0;
                    $inbox->save();

                    $woowa = Woowa::SendMessageSync()->setPhone($contact->phone)->setMessage($match->menu->content)->prepareContent()->send()->getResponse();
                    if ($woowa['status'] == true):
                        $inbox->has_reply = 1;
                        $inbox->save();
                    endif;
                    if ($match->menu->slug == 'contact-cs'):
                        $contact->update(['whitelist_until' => \Carbon\Carbon::now()->addHours($whiteListDuration)->toDateTimeString()]);
                        $this->sendDiscord($contact->phone);
                    endif;
                    return response()->json($match->menu->content);
                endif;
            endif;
        endif;
        return response()->json("OK end data");
    }

    public function sendDiscord($noHP)
    {
//        $msg[] = "**Hai team Support & SpearHead**";
//        $msg[] = "Ada yang membutuhkan bantuan via Whatsapp :";
//        $msg[] = "Nomer Whatsapp: ".$noHP;
//        $msg[] = "=============================================";
//        $this->sendDiscordNotification(sprintf('%s',implode("\n",$msg)), 'support');
    }
    public function sendSMS(Request $request){
        $no = '082241214466';
        dd($this->sendOTP($no,'kode otp : 1233'));
    }
}
