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
        $nots=Notification::whereDate('checkTime',Carbon::now()->addDay(7))
        ->whereIn('notifiable_type',['App\\Models\\Goal','App\\Models\\InternalCheck','App\\Models\\Supplier','App\\Models\\Complaint','App\\Models\\CorrectiveMeasure'])
        ->orWhere(function($query) {
            $query->whereDate('checkTime',Carbon::now()->addDay(15))
                  ->where('notifiable_type', 'App\\Models\\MeasuringEquipment');
        })->with('notifiable.standard')->get();

        if(!$nots->count()){
            return;
        }

        foreach($nots as $n){
            Mail::to(User::findOrFail(1))->send(new SendMailToAdmin($n));
        }
        /*
        foreach($nots as $n){
            $team=Team::find($n->team_id);
            App::setlocale($team->lang);
            $users=$team->allUsers();
            
            foreach($users as $u){
                $not_type= UserNotificationTypes::where('user_id',$u->id)->where('notifiable_type',$n->notifiable_type)->count();
                if(($u->hasTeamRole($team, 'admin') && $not_type) || ($u->hasTeamRole($team, 'editor') && (!$u->hasTeamRole($team, 'super-admin')) && $n->notifiable_type=='App\\Models\\InternalCheck' && Str::contains($n->notifiable->leaders, $u->name))){
                    if($u->email){
                        Mail::to($u)->send(new SendMailToAdmin($n));
                    }

                }
            }
        }  */
    }
}
