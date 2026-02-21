<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight transition-colors duration-300">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-2xl transition-colors duration-300
                        [&_h2]:dark:text-white [&_p]:dark:text-slate-400 [&_label]:dark:text-slate-300 
                        [&_input]:dark:bg-slate-900 [&_input]:dark:border-slate-700 [&_input]:dark:text-white [&_input:focus]:dark:border-indigo-500">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-2xl transition-colors duration-300
                        [&_h2]:dark:text-white [&_p]:dark:text-slate-400 [&_label]:dark:text-slate-300 
                        [&_input]:dark:bg-slate-900 [&_input]:dark:border-slate-700 [&_input]:dark:text-white [&_input:focus]:dark:border-indigo-500">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-2xl transition-colors duration-300
                        [&_h2]:dark:text-white [&_p]:dark:text-slate-400 [&_label]:dark:text-slate-300 
                        [&_input]:dark:bg-slate-900 [&_input]:dark:border-slate-700 [&_input]:dark:text-white [&_input:focus]:dark:border-indigo-500">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
