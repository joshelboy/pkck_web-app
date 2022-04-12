<template>
    <Head title="Log in" />

    <jet-authentication-card>
        <template #logo>
            <img src="https://www.simaxx.de/wp-content/uploads/2020/10/HU-Plakette_2022.png"/>
        </template>

        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div class="text-center large"><strong>Welcome!</strong></div>

        <div>
            <jet-button @click="loginWithDiscord" class="w-full my-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                <span class="mx-auto"> Sign In with Discord</span>
            </jet-button>
        </div>
    </jet-authentication-card>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetCheckbox from '@/Jetstream/Checkbox.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
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
                })
            }
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
            },
            loginWithDiscord(){
                window.location.href = '/auth/discord/redirect'
            }
        }
    })
</script>

<style scoped>
.large{
    font-size: 200%;
}
</style>