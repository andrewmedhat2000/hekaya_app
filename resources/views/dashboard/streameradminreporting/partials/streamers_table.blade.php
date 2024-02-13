
<table id="tbl-list-users" data-server="false" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>User Name</th>
            {{-- <th>Name</th> --}}
            {{-- <th>Email</th> --}}
            <th>Phone</th>
            <th>Agent</th>
            <th>Diamonds</th>
            <th>Level</th>
            <th>Basic Salary</th>
            <th>Days</th>
            <th>Minutes</th>
            <th>Host Reward</th>
            <th>Host Total</th>
            <th>Agent Commission</th>
            <th>App Profit</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{$user->id}}</td>

            <td>
                @if($links ?? true)
                    <a href="{{ route('dashboard.streamer.show',$user->id)}}">
                        {{$user->user_name}}
                    </a>
                @else
                    {{$user->user_name}}
                @endif
            </td>

            {{-- <td>{{$user->name}}</td> --}}
            {{-- <td>{{$user->email}}</td> --}}
            <td>{{$user->phone}}</td>
            <td>
                @if($links ?? true)
                    <a href="{{ route('dashboard.streameradmin.show',optional($user->currentStreamerAdminsPivot)->streamer_admin_id)}}">
                        {{optional($user->currentStreamerAdminsPivot)->streamerAdmin->name}}
                    </a>
                @else
                    {{optional($user->currentStreamerAdminsPivot)->streamerAdmin->name}}
                @endif
            </td>
            <td>
                <div>{{$users_diamonds[] = $user->getMonthlyGolds()}}</div>
            </td>
            <td>{{$user->getMonthlyLevel()}}</td>
            <td>
                <div>{{$users_salaries[] = $user->getMonthlyBasicSalary()}}</div>
                @if($links ?? true)
                    @php $progress = $user->getTargetProgress() @endphp
                    <progress style="width: 7rem" value="{{$progress['current'] - $progress['previous']}}" max="{{$progress['next'] - $progress['previous']}}"></progress>
                @endif

            </td>
            <td>{{$user->getMonthlyDaysCount()}}</td>
            <td>{{$users_minutes[] = $user->getMonthlyMinutes()}}</td>
            <td>{{$users_rewards[] = $user->getHoursReward()}}</td>
            <td>{{$users_money[] = $user->getMonthlyMoney()}}</td>
            <td>{{$users_commissions[] = $user->getMonthlyAdminCommission()}}</td>
            <td>{{$users_profits[] = round($user->getMonthlyGolds() / $diamonds_per_dollar - $user->getMonthlyMoney() - $user->getMonthlyAdminCommission(), 2)}}</td>
            {{-- <td>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <a class="btn btn-block btn-primary " style="width: 74.5px;" href="{{ route('dashboard.streamer.pay',['user_id'=>$user->id,'admin_id' => optional($user->streamerAdmins->first())->id]) }}">
                            Pay Streamer
                        </a>
                    </div>
                </div>
            </td> --}}
        </tr>
        @endforeach
        @if($users->isNotEmpty())
            <tr>
                <td><b>Total</b></td>
                <td></td>
                {{-- <td></td> --}}
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td><b>{{array_sum($users_diamonds)}}</b></td>
                <td></td>
                <td><b>{{array_sum($users_salaries)}}</b></td>
                <td></td>
                <td><b>{{array_sum($users_minutes)}}</b></td>
                <td><b>{{array_sum($users_rewards)}}</b></td>
                <td><b>{{array_sum($users_money)}}</b></td>
                <td><b>{{array_sum($users_commissions)}}</b></td>
                <td><b>{{array_sum($users_profits)}}</b></td>
            </tr>
        @endif
    </tbody>

</table>
