<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Mail\SendMailToAdmin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use App\Models\UserNotificationTypes;

class SendEmailToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-admins:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification email to admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(200);
        $nots=Notification::whereDate('checkTime',Carbon::now()->addDay(1))
        ->whereIn('notifiable_type',['App\\Models\\Goal','App\\Models\\InternalCheck','App\\Models\\Supplier'])
        ->orWhere(function($query) {
            $query->whereDate('checkTime',Carbon::now()->addDay(2))
                  ->where('notifiable_type', 'App\\Models\\MeasuringEquipment');
        })->with('notifiable.standard')->get();
        if(!$nots->count()){
            return;
        }
        $c=0;
       
        
        foreach($nots as $n){
            $team=Team::find($n->team_id);
            App::setlocale($team->lang);
        $u=User::find(4);
        Mail::to($u)->send(new SendMailToAdmin($nots[0]));return;
            $users=$team->allUsers();
            foreach($users as $u){
                $not_type= UserNotificationTypes::where('user_id',$u->id)->where('notifiable_type',$n->notifiable_type)->count();
                if(($u->hasTeamRole($team, 'admin') && $not_type) || ($u->hasTeamRole($team, 'editor') && (!$u->hasTeamRole($team, 'super-admin')) && $n->notifiable_type=='App\\Models\\InternalCheck' && Str::contains($n->notifiable->leaders, $u->name))){
                    $c++;
                     //Mail::to($u)->send(new SendMailToAdmin($n));
                }
            }
        }echo $c;
    }
}
