<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Contract,Property,WorkOrderLists,User,Service,TaskLists,TaskDetails,ContractServiceDate};
use App\Exports\Reports\Admin\{CompletedMaintenanceSchedule,WorkOrder,CompletedWorkOrderPerMonth,WorkOrderRequestedVsCompleted,UpcomingScheduleMaintenance,UpcomingSchedulePerWeek,ContractStatus,MaintenanceBacklog};

use App\Exports\Reports\Customer\{WorkOrderReport,MaintenanceScheduleReport};
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use DB;
use Carbon\CarbonPeriod;
class ReportController extends Controller
{
    public $data = array();            

    /*****************************************************/
    # Function name : index
    # Created Date  : 18-12-2020
    # Purpose       : To Load Report View Page                
    # Params        : 
    /*****************************************************/

    public function index(Request $request)
    {
        $this->data['page_title'] = 'Report';
        $this->data['current_user']=$current_user=auth()->guard('admin')->user();
        $user_type=$current_user->role->user_type->slug;
        
        switch ($user_type) {
            case "super-admin":
                return $this->admin_report($request,$current_user);
            break;
            case "property-owner":
                return $this->customer_report($request,$current_user);
            break;
            case "service-provider":
                return $this->service_provider_report($request,$current_user);
            break;
            case "labour":
                return $this->labour_report($request,$current_user);
            break;
            default:
                return $this->default_report($request,$current_user);
        }
        
    }

    public function schedule_compliance_report(Request $request){
        
        $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');

        $service_dates=ContractServiceDate::whereHas('contract')
        ->with([
            'service'=>function($q){
                $q->withTrashed();
            },
            'contract',
            'task_details_list.userDetails',
            'task_details_list',
            'task_details_list.task',
            'task_details_list.task.work_order',
            'task_details_list.task.userDetails'=>function($q){
                $q->withTrashed();
            },
            'contract.property'=>function($q){
                $q->withTrashed();
            },
            'contract.service_provider'=>function($q){
                $q->withTrashed();
            },
            'contract.property.owner_details'=>function($q){
                $q->withTrashed();
            },
            'contract.property.city'=>function($q){
                $q->withTrashed();
            }, 
 
        ])
        ->whereHas('task_details_list',function($q){
            $q->where('status','2');
        })
        ->where(function($q)use($request,$from_date,$to_date){
            $q->where('date','>=',$from_date)->where('date','<=',$to_date);
        })

        ->when($request->property_id && $request->property_id!='all',function($q)use($request){
            $q->whereHas('contract.property',function($q1)use($request){
                $q1->where('id',$request->property_id);
            });
        })
        ->when($request->contract_id && $request->contract_id!='all',function($q)use($request){
            $q->whereHas('contract',function($q1)use($request){
                $q1->where('id',$request->contract_id);
            });

        })
        ->get();

        return Excel::download(new CompletedMaintenanceSchedule($service_dates),'completed-schedule-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }


    public function maintenance_backlog_report(Request $request){
        $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');

        $service_dates=ContractServiceDate::whereHas('contract')
        ->with([
            'service'=>function($q){
                $q->withTrashed();
            },
            'contract',
            'task_details_list.userDetails',
            'task_details_list',
            'task_details_list.task',
            'task_details_list.task.work_order',
            'task_details_list.task.userDetails'=>function($q){
                $q->withTrashed();
            },
            'contract.property'=>function($q){
                $q->withTrashed();
            },
            'contract.service_provider'=>function($q){
                $q->withTrashed();
            },
            'contract.property.owner_details'=>function($q){
                $q->withTrashed();
            },
            'contract.property.city'=>function($q){
                $q->withTrashed();
            }, 
 
        ])
        ->whereHas('task_details_list',function($q){
            $q->where('status','!=','2');
        })
        ->when($request->sp_or_labour_id && $request->sp_or_labour_id!='0',function($q)use($request){

            $user=User::with(['role','role.user_type'])->whereHas('role')->whereHas('role.user_type')->where('id',$request->sp_or_labour_id)->first();
            if($user){

                $user_type=$user->role->user_type->slug;
                if($user_type=='labour'){

                    $q->whereHas('task_details_list',function($q1) use($request){
                        $q1->where('user_id',$request->sp_or_labour_id);
                    });

                }elseif ($user_type=='service-provider') {
                   $q->whereHas('contract',function($q1) use($request){
                    $q1->where('service_provider_id',$request->sp_or_labour_id);
                   });
                }
            }

        })
        ->where(function($q)use($request,$from_date,$to_date){
            $q->where('date','>=',$from_date)->where('date','<=',$to_date);
        })

        ->get();

        return Excel::download(new MaintenanceBacklog($service_dates),'maintenance-backlog-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function upcoming_weekly_maintenance_report(Request $request){

        $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
    


        $upcoming_service_dates=ContractServiceDate::whereHas('contract')
        ->with([
            'service'=>function($q){
                $q->withTrashed();
            },
            'contract',
            'task_details_list.userDetails',
            'task_details_list',
            'task_details_list.task',
            'task_details_list.task.work_order',
            'task_details_list.task.userDetails'=>function($q){
                $q->withTrashed();
            },
            'contract.property'=>function($q){
                $q->withTrashed();
            },
            'contract.service_provider'=>function($q){
                $q->withTrashed();
            },
            'contract.property.owner_details'=>function($q){
                $q->withTrashed();
            },
            'contract.property.city'=>function($q){
                $q->withTrashed();
            }, 
 
        ])
        ->when($request->service_id && $request->service_id!='0',function($q)use($request){
            $q->whereHas('service',function($q1)use($request){
                $q1->where('id',$request->service_id);
            });
        })
        ->when($request->sp_or_labour_id && $request->sp_or_labour_id!='0',function($q)use($request){

            $user=User::with(['role','role.user_type'])->whereHas('role')->whereHas('role.user_type')->where('id',$request->sp_or_labour_id)->first();
            if($user){

                $user_type=$user->role->user_type->slug;
                if($user_type=='labour'){

                    $q->whereHas('task_details_list',function($q1) use($request){
                        $q1->where('user_id',$request->sp_or_labour_id);
                    });

                }elseif ($user_type=='service-provider') {
                   $q->whereHas('contract',function($q1) use($request){
                    $q1->where('service_provider_id',$request->sp_or_labour_id);
                   });
                }

            }


        })
        ->where(function($q)use($request,$from_date,$to_date){
            $q->where('date','>=',$from_date)->where('date','<=',$to_date);
        })->get();

        $upcoming_weekly_services=[];
        
        $last_week_last_date=Carbon::parse($to_date)->endOfWeek()->format('Y-m-d');
        
        $start_date=$from_date;

        while ($start_date <= $last_week_last_date) {

            $start_date_of_week=Carbon::parse($start_date)->startOfWeek()->format('Y-m-d');
            $end_date_of_week=Carbon::parse($start_date)->endOfWeek()->format('Y-m-d');

            $week_number=Carbon::parse($start_date)->weekOfYear;
            $year=Carbon::parse($start_date)->format('Y');

            $effective_from=($from_date>$start_date_of_week)?$from_date:$start_date_of_week;
            $effective_to=($to_date<$end_date_of_week)?$to_date:$end_date_of_week;


            $filtered = $upcoming_service_dates->filter(function ($service_date, $key)use($effective_from,$effective_to) {

                if($service_date->date>=$effective_from && $service_date->date<=$effective_to){
                    return true;
                }else{
                    return false;
                }

            });
            $upcoming_service_dates_list=$filtered->all();

            $upcoming_weekly_services[]=[
                'week_number'=>$week_number,
                'year'=>$year,
                'effective_from'=>$effective_from,
                'effective_to'=>$effective_to,
                'upcoming_service_dates'=>$upcoming_service_dates_list
            ];


            $start_date=Carbon::parse($start_date)->addWeek(1)->format('Y-m-d');
        }
        //dd($upcoming_weekly_services);

        return Excel::download(new UpcomingSchedulePerWeek($upcoming_weekly_services),'upcoming-weekly-schedule-maintenance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }


    public function upcoming_schedule_maintenance_report(Request $request){

        $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');

        $upcoming_service_dates=ContractServiceDate::whereHas('contract')
        ->with([
            'service'=>function($q){
                $q->withTrashed();
            },
            'contract',
            'task_details_list.userDetails',
            'task_details_list',
            'task_details_list.task',
            'task_details_list.task.work_order',
            'task_details_list.task.userDetails'=>function($q){
                $q->withTrashed();
            },
            'contract.property'=>function($q){
                $q->withTrashed();
            },
            'contract.service_provider'=>function($q){
                $q->withTrashed();
            },
            'contract.property.owner_details'=>function($q){
                $q->withTrashed();
            },
            'contract.property.city'=>function($q){
                $q->withTrashed();
            }, 
 
        ])
        ->where(function($q)use($request,$from_date,$to_date){
            $q->where('date','>=',$from_date)->where('date','<=',$to_date);
        })->get();

        return Excel::download(new UpcomingScheduleMaintenance($upcoming_service_dates),'upcoming-schedule-maintenance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function work_order_report(Request $request){

        $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
        
        $work_orders=WorkOrderLists::whereHas('contract')
        ->whereHas('service')
        ->with([
            'contract',
            'service_provider'=>function($q){
                $q->withTrashed();
            },
            'property'=>function($q){
                $q->withTrashed();
            },
            'property.owner_details'=>function($q){
                $q->withTrashed();
            },
            'property.city'=>function($q){
                $q->withTrashed();
            }, 
            'service'=>function($q){
                $q->withTrashed();
            },  
        ])
        ->where(function($q)use($request,$from_date,$to_date){
           if($request->work_order_status=='closed'){
            $q->where('status','2')->where('work_order_complete_date','>=',$from_date)->where('work_order_complete_date','<=',$to_date);
           }else{
            if ($request->work_order_status=='requested') {
                $q->where('status','0')->where('start_date','>=',$from_date)->where('start_date','<=',$to_date);
            }elseif ($request->work_order_status=='overdue') {
                $q->where('status','1')->where('start_date','>=',$from_date)->where('start_date','<=',$to_date);
            }else{
                $q->where('start_date','>=',$from_date)->where('start_date','<=',$to_date);
            }
            
           }
        })
        ->orderBy('id','desc')->get();

        $file_name=ucfirst($request->work_order_status).'-work-order-report-from-'.$from_date.'-to-'.$to_date.'.csv';

        return Excel::download(new WorkOrder($work_orders),$file_name, \Maatwebsite\Excel\Excel::CSV);
    }
    public function work_order_completed_per_month_report(Request $request){
        
        $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
        
        $period =CarbonPeriod::create($from_date, '1 month',$to_date);
        $month_year_array=[];
        foreach ($period as $dt) {
             $month_year_array[]= $dt->format("m-Y");
        }

        $work_orders_count=WorkOrderLists::where('status','2')
        ->where(function($q)use($request,$from_date,$to_date){
            $q->where('start_date','>=',$from_date)->where('start_date','<=',$to_date);
        })
        ->select(
            DB::raw('count(*) as total_wo,  DATE_FORMAT(start_date, "%m-%Y") as month_year')
        )->groupBy('month_year')->pluck('total_wo','month_year')->toArray();

        $work_orders=WorkOrderLists::where('status','2')->whereHas('contract')
        ->whereHas('service')
        ->with([
            'contract',
            'service_provider'=>function($q){
                $q->withTrashed();
            },
            'property'=>function($q){
                $q->withTrashed();
            },
            'property.owner_details'=>function($q){
                $q->withTrashed();
            },
            'property.city'=>function($q){
                $q->withTrashed();
            }, 
            'service'=>function($q){
                $q->withTrashed();
            },  
        ])
        ->where(function($q)use($request,$from_date,$to_date){
            $q->where('start_date','>=',$from_date)->where('start_date','<=',$to_date);
        })
        ->orderBy('id','desc')->get();

        $data=[];

        foreach ($month_year_array as $key=>$month_year){
            
            $total_wo=(array_key_exists($month_year,$work_orders_count))?$work_orders_count[$month_year]:0;
           
            $filtered = $work_orders->filter(function ($work_order, $key)use($month_year) {

                $wo_month_year=Carbon::parse($work_order->start_date)->format('m-Y');
                return $wo_month_year==$month_year;

            });
            $work_order_list=$filtered->all();

            $year_month=Carbon::createFromFormat('m-Y', $month_year)->format('Y-m');

            $first_day_of_month = (new Carbon('first day of '.$year_month))->format('Y-m-d');
            $last_day_of_month = (new Carbon('last day of '.$year_month))->format('Y-m-d');
         
            $effective_from=($from_date>$first_day_of_month)?$from_date:$first_day_of_month;
            $effective_to=($to_date<$last_day_of_month)?$to_date:$last_day_of_month;

            $data[$month_year]=[
                'total_wo'=>$total_wo,
                'effective_from'=>$effective_from,
                'effective_to'=>$effective_to,
                'work_orders'=>$work_order_list
            ];
        }
        
        return Excel::download(new CompletedWorkOrderPerMonth($data),'completed-work-order-per-month-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function work_order_requested_vs_completed_report(Request $request){

        $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
        
        $dates=CarbonPeriod::create($from_date,'1 days', $to_date);
        $dates_array=[];
        if(count($dates)){
            foreach ($dates as $key => $date) {
                $dates_array[] = $date->format('Y-m-d');
            }
        }

        $requested_work_orders=WorkOrderLists::where('status','0')
        ->where(function($q)use($request,$from_date,$to_date){
            $q->where('start_date','>=',$from_date)->where('start_date','<=',$to_date);
        })
        ->select(
            DB::raw('count(*) as total_requested_wo,start_date')
        )->groupBy('start_date')->pluck('total_requested_wo','start_date')->toArray();

        $completed_work_orders=WorkOrderLists::where('status','2')
        ->where(function($q)use($request,$from_date,$to_date){
            $q->where('start_date','>=',$from_date)->where('start_date','<=',$to_date);
        })
        ->whereNotNull('work_order_complete_date')
        ->select(
            DB::raw('count(*) as total_completed_wo,work_order_complete_date')
        )->groupBy('work_order_complete_date')->pluck('total_completed_wo','work_order_complete_date')->toArray();

        $data=[];

        if (count($dates_array)) {
            foreach ($dates_array as $date) {

                $total_requested_work_orders=(array_key_exists($date,$requested_work_orders))?$requested_work_orders[$date]:0;

                $total_completed_work_orders=(array_key_exists($date,$completed_work_orders))?$completed_work_orders[$date]:0;

                $data[]=[
                'date'=>$date,
                'requested_work_orders'=>$total_requested_work_orders,
                'completed_work_orders'=>$total_completed_work_orders
                ]; 
            }
        }

        return Excel::download(new WorkOrderRequestedVsCompleted($data),'work_order_requested_vs_completed_report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function contract_status_report(Request $request){
        
        $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');


        $contracts=Contract::with(['property','service_provider','services','contract_status'])
        ->whereHas('property')
        ->whereHas('service_provider')
        ->whereHas('contract_status',function($q){
            $q->where('slug','ongoing');
        })
        ->where(function($q) use ($from_date,$to_date){
            $q->where(function($q) use ($from_date,$to_date){
              $q->where('end_date','>=',$from_date)->where('start_date','<=',$from_date);
            })
            ->orWhere(function($q) use ($from_date,$to_date){
              $q->where('end_date','>=',$to_date)->where('start_date','<=',$to_date);
            })
            ->orWhere(function($q) use ($from_date,$to_date){
              $q->where('end_date','<=',$to_date)->where('start_date','>=',$from_date);
            });
        })
        ->withCount([
            'work_orders as completed_work_orders'=>function($q){
            $q->where('status','2');
            },
            'work_orders as pending_work_orders'=>function($q){
            $q->where('status','0');
            },

        ])
        ->get();


        return Excel::download(new ContractStatus($contracts),'contract-report.csv', \Maatwebsite\Excel\Excel::CSV,[
            'Content-Type' => 'text/csv',
        ]);
    }

    public function admin_report($request,$current_user){
        $this->data['contracts']=Contract::whereHas('property')
        ->whereHas('property.owner_details')
        ->whereHas('contract_status')
        ->whereHas('service_provider')
        ->where('creation_complete',true)
        ->orderBy('id','desc')->get();

    	$this->data['properties']=Property::whereHas('owner_details')
        ->orderBy('id','desc')->get();

        $this->data['service_providers']=User::whereStatus('A')->whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
            $q->where('slug','service-provider');
        })->get();

        $this->data['labours']=User::whereStatus('A')->whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
            $q->where('slug','labour');
        })->get();

        $this->data['services']=Service::whereIsActive(true)->get();

        return view('admin.report.admin.index',$this->data);
    }
    public function customer_report($request,$current_user){

        $this->data['contracts']=Contract::whereHas('property',function($q)use($current_user){
            $q->where('property_owner',$current_user->id)->orWhere('property_manager',$current_user->id);
        })
        ->whereHas('property.owner_details')
        ->whereHas('contract_status')
        ->whereHas('service_provider')
        ->where('creation_complete',true)
        ->orderBy('id','desc')->get();

        $this->data['properties']=Property::whereHas('owner_details')
        ->where(function($q)use($current_user){
            $q->where('property_owner',$current_user->id)->orWhere('property_manager',$current_user->id);
        })
        ->orderBy('id','desc')->get();

        if($request->isMethod('post')){
            $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
            $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
            if($request->report_on=='work_order'){


                $work_orders=WorkOrderLists::whereHas('contract')
                ->whereHas('service')
                ->with([
                    'contract',
                    'service_provider'=>function($q){
                        $q->withTrashed();
                    },
                    'property'=>function($q){
                        $q->withTrashed();
                    },
                    'property.owner_details'=>function($q){
                        $q->withTrashed();
                    },
                    'property.city'=>function($q){
                        $q->withTrashed();
                    }, 
                    'service'=>function($q){
                        $q->withTrashed();
                    },  
                ])
                ->whereHas('property',function($q)use($current_user){
                    $q->where('property_owner',$current_user->id)->orWhere('property_manager',$current_user->id);
                })
                ->when($request->contract_id && $request->contract_id!='all',function($q)use($request){
                    $q->where('contract_id',$request->contract_id);

                })
                ->when($request->property_id && $request->property_id!='all',function($q)use($request){
                    $q->where('property_id',$request->property_id);

                })
                ->where(function($q)use($request,$from_date,$to_date){
                   if($request->service_status=='completed'){
                    $q->where('status','2')->where('work_order_complete_date','>=',$from_date)->where('work_order_complete_date','<=',$to_date);
                   }elseif($request->service_status=='due'){

                    $q->whereIn('status',['0','1'])->where('start_date','>=',$from_date)->where('start_date','<=',$to_date);
                   }
                })
                ->orderBy('id','desc')->get();

                return Excel::download(new WorkOrderReport($work_orders),'work-order-report.csv', \Maatwebsite\Excel\Excel::CSV,[
                    'Content-Type' => 'text/csv',
                ]);


            }elseif ($request->report_on=='maintenance_schedule') {
              

                $service_dates=ContractServiceDate::whereHas('contract')
                ->whereHas('contract.property',function($q)use($current_user){
                    $q->where('property_owner',$current_user->id)->orWhere('property_manager',$current_user->id);
                })
                ->with([
                    'service'=>function($q){
                        $q->withTrashed();
                    },
                    'contract',
                    'task_details_list.userDetails',
                    'task_details_list',
                    'task_details_list.task',
                    'task_details_list.task.work_order',
                    'task_details_list.task.userDetails'=>function($q){
                        $q->withTrashed();
                    },
                    'contract.property'=>function($q){
                        $q->withTrashed();
                    },
                    'contract.service_provider'=>function($q){
                        $q->withTrashed();
                    },
                    'contract.property.owner_details'=>function($q){
                        $q->withTrashed();
                    },
                    'contract.property.city'=>function($q){
                        $q->withTrashed();
                    }, 
         
                ])
                ->whereHas('task_details_list',function($q)use($request){
                    $q->where('status','2');

                   if($request->service_status=='completed'){
                    $q->where('status','2');
                   }elseif($request->service_status=='due'){
                    $q->where('status',['0','1']);
                   }

                })
                ->where(function($q)use($request,$from_date,$to_date){
                    $q->where('date','>=',$from_date)->where('date','<=',$to_date);
                })
                ->when($request->property_id && $request->property_id!='all',function($q)use($request){
                    $q->whereHas('contract.property',function($q1)use($request){
                        $q1->where('id',$request->property_id);
                    });
                })
                ->when($request->contract_id && $request->contract_id!='all',function($q)use($request){
                    $q->whereHas('contract',function($q1)use($request){
                        $q1->where('id',$request->contract_id);
                    });

                })
                ->get();


                return Excel::download(new MaintenanceScheduleReport($service_dates),'schedule-maintenance-report.csv', \Maatwebsite\Excel\Excel::CSV,[
                    'Content-Type' => 'text/csv',
                ]);



            } 

        }
    	return view('admin.report.customer.index',$this->data);
    }
    public function service_provider_report($request,$current_user){
    	return view('admin.report.service_provider.index',$this->data);
    }
    public function labour_report($request,$current_user){
        return view('admin.report.labour.index',$this->data);
    }
    public function default_report($request,$current_user){
       return view('admin.dashboard.default',$this->data); 
    }
}
