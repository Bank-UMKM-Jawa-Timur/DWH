<div class="layout-navigation z-40">
    <div>
        <button class="toggle-sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 17h14M5 12h14M5 7h14" />
            </svg>
        </button>
        <button class="mt-2 ml-3 toggle-fullscreen">
            <span class="unfullscreen hidden">
                @include('components.svg.unfullscreen')
            
            </span>
            <span class="fullscreen">
                @include('components.svg.fullscreen')
            </span>
        </button>
    </div>
    <div class="flex gap-8 relative">
        <!-- notification toggle -->
        <button class="toggle-notification">
            <span class="p-2 absolute -mt-1 rounded-full bg-theme-primary"></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 36 36">
                <path fill="currentColor"
                    d="M32.51 27.83A14.4 14.4 0 0 1 30 24.9a12.63 12.63 0 0 1-1.35-4.81v-4.94A10.81 10.81 0 0 0 19.21 4.4V3.11a1.33 1.33 0 1 0-2.67 0v1.31a10.81 10.81 0 0 0-9.33 10.73v4.94a12.63 12.63 0 0 1-1.35 4.81a14.4 14.4 0 0 1-2.47 2.93a1 1 0 0 0-.34.75v1.36a1 1 0 0 0 1 1h27.8a1 1 0 0 0 1-1v-1.36a1 1 0 0 0-.34-.75ZM5.13 28.94a16.17 16.17 0 0 0 2.44-3a14.24 14.24 0 0 0 1.65-5.85v-4.94a8.74 8.74 0 1 1 17.47 0v4.94a14.24 14.24 0 0 0 1.65 5.85a16.17 16.17 0 0 0 2.44 3Z"
                    class="clr-i-outline clr-i-outline-path-1" />
                <path fill="currentColor" d="M18 34.28A2.67 2.67 0 0 0 20.58 32h-5.26A2.67 2.67 0 0 0 18 34.28Z"
                    class="clr-i-outline clr-i-outline-path-2" />
                <path fill="none" d="M0 0h36v36H0z" />
            </svg>
        </button>
        @php
            $notification = \App\Models\Notification::select(
                'notifications.id',
                'notifications.user_id',
                'notifications.extra',
                'notifications.read',
                'notifications.created_at',
                'notifications.updated_at',
                'nt.title',
                'nt.content',
                'nt.action_id',
                'nt.role_id',
            )
            ->join('notification_templates AS nt', 'nt.id', 'notifications.template_id')
            ->where('notifications.user_id', \Session::get(config('global.user_id_session')))
            ->orderBy('notifications.read')
            ->orderBy('notifications.created_at', 'DESC')
            ->limit(5)
            ->get();

            $notif_belum_dibaca = \App\Models\Notification::select('notifications.id')
                            ->join('users AS u', 'u.id', 'notifications.user_id')
                            ->where('u.id', \Session::get(config('global.user_id_session')))
                            ->where('notifications.read', false)
                            ->count();
        @endphp
        <div class="notification-list hidden border lg:w-96 w-80 bg-white absolute top-10 lg:right-20 right-0">
            <div class="head border-b w-full text-center p-3">
                Notification
                <span class="ml-3 py-1.5 px-3 text-white rounded-full bg-theme-primary">
                    {{$notif_belum_dibaca}}</span>
            </div>
            @forelse ($notification as $item)
                <div class="notif-list grid grid-cols-1 bg-white">
                    <button class="w-full text-left border-b p-3">
                        <p class="text-xs text-theme-primary">{{$item->read ? 'Sudah dibaca' : 'Belum dibaca'}}</p>
                        <p class="font-bold">{{$item->title}}</p>
                        <p class="text-xs text-theme-text">{{ date('Y-m-d H:i', strtotime($item->created_at)) }}</p>
                    </button>
                </div>
            @empty
                <p>Belum ada notifikasi</p>
            @endforelse
            <div class="footer-notif text-center p-3">
                <button>Lihat semua</button>
            </div>
        </div>
        <!-- avatar -->
        <button class="avatar dropdown-account-toggle">
            <img src="https://ui-avatars.com/api/?name={{$name}}" class="rounded-full" alt="" srcset="">
        </button>
        {{--  <button class="avatar dropdown-account-toggle">
            <p class="text-white">A</p>
        </button>  --}}
        <div class="dropdown-account hidden bg-white z-30 w-80 divide-y absolute border right-0 top-11">
            <div class="head flex gap-5 p-5">
                <button class="avatar">
                    <img src="https://ui-avatars.com/api/?name={{$name}}" class="rounded-full" alt="" srcset="">
                </button>
                <div>
                    <h2 class="text-theme-text font-semibold">
                        {{$name}}
                    </h2>
                    <p class="text-gray-400">{{$sub_name}}</p>
                </div>
            </div>
            <a href="" class="block"><button class="p-4 w-full text-left hover:bg-gray-200">
                    Reset Password
                </button></a>
            <a href="#" class="block"><button type="button" class="p-4 w-full text-left hover:bg-gray-200"
                id="btn-logout">
                    Log out
                </button></a>
        </div>
    </div>
</div>
</div>
