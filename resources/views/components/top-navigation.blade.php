<div class="layout-navigation z-40">
    <div>
    <button class="toggle-sidebar">
        <svg
        xmlns="http://www.w3.org/2000/svg"
        width="25"
        height="25"
        viewBox="0 0 24 24"
        >
        <path
            fill="none"
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M5 17h14M5 12h14M5 7h14"
        />
        </svg>
    </button>
    <button
        class="mt-2 ml-3"
        id="fullscreen"
    >
        <svg
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        >
        <g
            fill="none"
            fill-rule="evenodd"
        >
            <path
            d="M24 0v24H0V0h24ZM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01l-.184-.092Z"
            />
            <path
            fill="currentColor"
            d="M18.5 5.5H16a1.5 1.5 0 0 1 0-3h3A2.5 2.5 0 0 1 21.5 5v3a1.5 1.5 0 0 1-3 0V5.5ZM8 5.5H5.5V8a1.5 1.5 0 1 1-3 0V5A2.5 2.5 0 0 1 5 2.5h3a1.5 1.5 0 1 1 0 3Zm0 13H5.5V16a1.5 1.5 0 0 0-3 0v3A2.5 2.5 0 0 0 5 21.5h3a1.5 1.5 0 0 0 0-3Zm8 0h2.5V16a1.5 1.5 0 0 1 3 0v3a2.5 2.5 0 0 1-2.5 2.5h-3a1.5 1.5 0 0 1 0-3Z"
            />
        </g>
        </svg>
    </button>
    </div>
    <div class="flex gap-8 relative">
    <!-- notification toggle -->
    <button class="toggle-notification">
        <span
        class="p-2 absolute -mt-1 rounded-full bg-theme-primary"
        ></span>
        <svg
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        viewBox="0 0 36 36"
        >
        <path
            fill="currentColor"
            d="M32.51 27.83A14.4 14.4 0 0 1 30 24.9a12.63 12.63 0 0 1-1.35-4.81v-4.94A10.81 10.81 0 0 0 19.21 4.4V3.11a1.33 1.33 0 1 0-2.67 0v1.31a10.81 10.81 0 0 0-9.33 10.73v4.94a12.63 12.63 0 0 1-1.35 4.81a14.4 14.4 0 0 1-2.47 2.93a1 1 0 0 0-.34.75v1.36a1 1 0 0 0 1 1h27.8a1 1 0 0 0 1-1v-1.36a1 1 0 0 0-.34-.75ZM5.13 28.94a16.17 16.17 0 0 0 2.44-3a14.24 14.24 0 0 0 1.65-5.85v-4.94a8.74 8.74 0 1 1 17.47 0v4.94a14.24 14.24 0 0 0 1.65 5.85a16.17 16.17 0 0 0 2.44 3Z"
            class="clr-i-outline clr-i-outline-path-1"
        />
        <path
            fill="currentColor"
            d="M18 34.28A2.67 2.67 0 0 0 20.58 32h-5.26A2.67 2.67 0 0 0 18 34.28Z"
            class="clr-i-outline clr-i-outline-path-2"
        />
        <path
            fill="none"
            d="M0 0h36v36H0z"
        />
        </svg>
    </button>
    <div
        class="notification-list hidden border lg:w-96 w-80 bg-white absolute top-10 lg:right-20 right-0"
    >
        <div class="head border-b w-full text-center p-3">
        Notification
        <span
            class="ml-3 py-1.5 px-3 text-white rounded-full bg-theme-primary"
        >
            5</span
        >
        </div>
        <div class="notif-list grid grid-cols-1 bg-white">
        <button class="w-full text-left border-b p-3">
            <p class="text-xs text-theme-primary">Belum dibaca</p>
            <p class="font-bold">Hello world</p>
            <p class="text-xs text-theme-text">07-03-2023</p>
        </button>
        </div>
        <div class="footer-notif text-center p-3">
        <button>Lihat semua</button>
        </div>
    </div>
    <!-- avatar -->
    <button class="avatar dropdown-account-toggle"></button>
    <div
        class="dropdown-account hidden bg-white z-30 w-80 divide-y absolute border right-0 top-11"
    >
        <div class="head flex gap-5 p-5">
        <div class="avatar"></div>
        <div>
            <h2 class="text-theme-text font-semibold">
            Arsyad Arthan N.
            </h2>
            <p class="text-gray-400">19275</p>
        </div>
        </div>
        <a
        href=""
        class="block"
        ><button class="p-4 w-full text-left hover:bg-gray-200">
            Reset Password
        </button></a
        >
        <a
        href=""
        class="block"
        ><button class="p-4 w-full text-left hover:bg-gray-200">
            Log out
        </button></a
        >
    </div>
    </div>
</div>