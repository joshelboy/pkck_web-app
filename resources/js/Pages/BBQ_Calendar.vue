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

                    <div class="px-20 my-10">

                        <div class="grid grid-cols-8 gap-4 my-2">
                            <div class="font-bold text-2xl">Übersicht</div>
                            <div class="text-xs my-2">[Work in Progress]</div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <jet-button @click="createEvent"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                Event erstellen</jet-button>
                        </div>

                        <div>
                            <div class="grid grid-cols-8 gap-4 font-bold">
                                <div>ID</div>
                                <div>Titel</div>
                                <div>Uhrzeit</div>
                                <div>Datum</div>
                                <div>Ort</div>
                                <div>Ersteller</div>
                            </div>
                        </div>

                        <div v-for="event in events" :key="event.id">
                            <hr>

                            <div v-if="event.userCreated == currentUser">
                                <div class="grid grid-cols-8 gap-4 my-2">
                                    <div>{{ event.id }}</div>
                                    <div>{{ event.title }}</div>
                                    <div>{{ event.time }}</div>
                                    <div>{{ event.date }}</div>
                                    <div>{{ event.location }}</div>
                                    <div>{{ event.userName }}</div>
                                    <button @click="updateEvent(event.id)"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold h-10 px-5 my-2 rounded text-center">Bearbeiten</button>
                                    <button
                                        class="hover:bg-red-500 hover:text-white text-black font-bold h-10 px-5 m-2 rounded text-center"
                                        @click="deleteEvent(event.id)">Löschen</button>
                                </div>
                            </div>

                            <div v-if="event.userCreated != currentUser">
                                <div class="grid grid-cols-8 gap-4 my-2">
                                    <div>{{ event.id }}</div>
                                    <div>{{ event.title }}</div>
                                    <div>{{ event.time }}</div>
                                    <div>{{ event.date }}</div>
                                    <div>{{ event.location }}</div>
                                    <div>{{ event.userCreated }}</div>
                                    <button @click="updateEvent(event.id)"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold h-10 px-5 my-2 rounded text-center">Bearbeiten</button>
                                    <button
                                        class="hover:bg-red-500 hover:text-white text-black font-bold h-10 px-5 m-2 rounded opacity-50 cursor-not-allowed text-center">Löschen</button>
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
    data() {
        return {
            events: [],
            currentUser: {},
            users: []
        }
    },
    created() {
        try {
            axios
                .get('/api/events/')
                .then(response => {

                    
                    this.events = response.data;
                    
                    axios.get("/api/users").then((response) => {
                        this.users = response.data;
                        console.log(response)

                        var eventsLength = this.events.length;
                        var usersLength = this.users.length;

                        for (var i = 0; i < eventsLength; i++) {
                            for (var j = 0; j < usersLength; j++){
                                if (this.users[j].id == this.events[i].userCreated) {
                                    this.events[i].userName = this.users[j].name;
                                }
                            }
                        }

                    });
                    
                });

            axios.get("/api/user").then((response) => {
                this.currentUser = response.data.id;
            });

        }
        catch (err) {
            console.log(err)
        }
    },
    methods: {
        deleteEvent(id) {
            axios
                .delete(`/api/events/${id}`)
                .then(response => {
                    let i = this.events.map(data => data.id).indexOf(id);
                    this.events.splice(i, 1)
                });
        },
        createEvent() {
            window.location.href = '/bbq/create'
        },
        updateEvent(id) {
            window.location.href = `/bbq/edit/` + id
        }
    }
})
</script>