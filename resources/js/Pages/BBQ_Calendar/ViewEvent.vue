<template>
    <app-layout title="BBQ Calendar">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                BBQ Calendar
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="max-w-xl mx-auto py-12 divide-y md:max-w-4xl">
                        <h2 class="text-2xl font-bold">
                            Event informationen
                        </h2>

                        <div class="mt-8 max-w-md">
                            <div class="grid grid-cols-1 gap-6">
                                <div></div>

                                <label class="block">
                                    <span class="text-gray-700 text-xl font-bold">Titel</span>
                                    <br>
                                    <div> {{event.title}} </div>
                                </label>

                                <label class="block">
                                    <span class="text-gray-700 text-xl font-bold">Datum</span>
                                    <br>
                                    <div> {{event.date}} </div>
                                </label>

                                <label class="block">
                                    <span class="text-gray-700 text-xl font-bold">Uhrzeit</span>
                                    <br>
                                    <div> {{event.time}} </div>
                                </label>

                                <label class="block">
                                    <span class="text-gray-700 text-xl font-bold">Standort</span>
                                    <br>
                                    <div> {{event.location}} </div>
                                </label>

                                <!--Invites-->

                                <div class="block">
                                    <div>

                                        <div class="text-xl font-bold">Einladungen</div>

                                        <template v-for="invitedUser in status" :key="invitedUser.userID">
                                            <template v-for="user in users" :key="user.id">

                                                <div v-if="invitedUser.userID == user.oauth_id">

                                                    <!--Emoji for status-->

                                                    <!--Accepted-->
                                                    <span v-if='invitedUser.status == "creator"'>
                                                        <span> ✅ </span>
                                                    </span>

                                                    <span v-if='invitedUser.status == "accepted"'>
                                                        <span> ✅ </span>
                                                    </span>



                                                    <!--Tentiative-->

                                                    <span v-if='invitedUser.status == "created"'>
                                                        <span> ⚠️ </span>
                                                    </span>

                                                    <span v-if='invitedUser.status == "sent"'>
                                                        <span> ⚠️ </span>
                                                    </span>


                                                    <!--Decline-->

                                                    <span v-if='invitedUser.status == "decline"'>
                                                        <span> ⛔ </span>
                                                    </span>


                                                    <span> - </span>
                                                    <span> {{user.name}} </span>
                                                </div>

                                            </template>
                                        </template>
                                        
                                        <span class="text-gray-700 italic mt-1">Einladungen können aktuell noch nicht
                                            nachträglich geändert werden.</span>

                                        <br>
                                        <br>

                                        <jet-button class="btn btn-primary" @click="back()">
                                            Zurück
                                        </jet-button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
  
<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'

export default defineComponent({
    components: {
        AppLayout,
        JetButton,
        JetInput
    },
    props: {

        id: String,

    },
    data() {
        return {
            event: {},
            status: [],
            users: [],
        }
    },
    created() {
        axios
            .get(`/api/events/${this.id}`)
            .then((res) => {
                this.event = res.data;
            });

        axios
            .get(`/api/status/${this.id}`)
            .then((res) => {
                this.status = res.data;
            });

        axios
            .get(`/api/users`)
            .then((res) => {
                this.users = res.data;
            });

    },
    methods: {
        back() {
            window.location.href = "/bbq/all";
        },
    }
})
</script>