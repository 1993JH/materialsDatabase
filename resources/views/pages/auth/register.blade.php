<x-layouts::auth :title="__('Register')" container-class="max-w-6xl">
    <main class="mx-auto w-full max-w-3xl px-6 pb-16 pt-4">
        <section class="about-fade-up mx-auto w-full max-w-md overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/80 shadow-sm backdrop-blur">
            <div class="about-gradient-shift border-b border-zinc-200/70 bg-linear-to-r from-amber-100/70 via-cyan-100/70 to-teal-100/70 px-6 py-6 md:px-8">
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700">Create Account</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Create an account</h1>
                <p class="mt-2 text-sm text-zinc-700">Enter your details below to create your account.</p>
            </div>

            <div class="about-fade-up about-delay-1 grid gap-6 p-6 md:p-8">
                <div class="mx-auto w-full max-w-xs rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm">
                    <x-auth-session-status class="mx-auto mb-4 w-full max-w-[13.5rem] text-center font-medium text-sm text-green-600" :status="session('status')" />

                    <form method="POST" action="{{ route('register.store') }}" class="mx-auto flex w-full max-w-[13.5rem] flex-col gap-6">
                        @csrf

                        <flux:input
                            name="name"
                            :label="__('Name')"
                            :value="old('name')"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            :placeholder="__('Full name')"
                        />

                        <flux:input
                            name="email"
                            :label="__('Email address')"
                            :value="old('email')"
                            type="email"
                            required
                            autocomplete="email"
                            placeholder="email@example.com"
                        />

                        <flux:input
                            name="password"
                            :label="__('Password')"
                            type="password"
                            required
                            autocomplete="new-password"
                            :placeholder="__('Password')"
                            viewable
                        />

                        <flux:input
                            name="password_confirmation"
                            :label="__('Confirm password')"
                            type="password"
                            required
                            autocomplete="new-password"
                            :placeholder="__('Confirm password')"
                            viewable
                        />

                        <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                            {{ __('Create account') }}
                        </flux:button>
                    </form>

                    <div class="mx-auto mt-5 w-full max-w-[13.5rem] space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600">
                        <span>{{ __('Already have an account?') }}</span>
                        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts::auth>

