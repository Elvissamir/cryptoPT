<template>
    <jet-authentication-card>
        <div class="flex justify-center mx-auto mt-3 mb-5">
           <Logo></Logo>
        </div>

        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div class="flex flex-col justify-center mt-5 mb-3">
            <p class="mt-3 mx-auto text-lg text-blue-900">Login as Demo User:</p>
            <div class="flex flex-col mt-4 mx-auto justify-center">
                <p class="mx-auto text-lg font-black">
                    <font-awesome-icon :icon="['fas', 'user']" :size="lg" />
                </p>
                <Link class="mt-2 bg-blue-900 text-lg px-4 py-2 font-black text-white rounded-md" method="post" as="button" type="button" :href="loginAsDemoUrl">Demo</Link>
            </div>
        </div>

        <div class="flex flex-col justify-center mt-8">
            <hr class="w-11/12 mx-auto">
            <p class="mt-3 mx-auto text-lg">Or Login:</p>
        </div>

        <form @submit.prevent="submit">
            <div>
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
            </div>

            <div class="mt-4">
                <jet-label for="password" value="Password" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <jet-checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </inertia-link>

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </jet-button>
            </div>
        </form>

    </jet-authentication-card>
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    import Logo from '../../Components/Logo.vue'
    import {Link} from '@inertiajs/inertia-vue3'

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            Logo,
            Link,
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                }),
                loginAsDemoUrl: '/loginAsDemo'
            }
        },
        mounted() {
            document.title = "CPT - Login";
        },
        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            }
        }
    }
</script>
