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

                    <div class="px-20 my-10 grid grid-cols-4 gap-8">
                    <div class="col-span-3">

                        <div class="grid grid-cols-6 gap-4 my-2">
                            <div class="font-bold text-2xl">Übersicht</div>
                            <div class="text-xs my-2"></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <jet-button @click="createEvent"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center col-span-auto">
                                Event erstellen</jet-button>
                        </div>

                        <div>
                            <div class="grid grid-cols-6 gap-4 font-bold">
                                <div>Titel</div>
                                <div>Datum</div>
                                <div>Uhrzeit</div>
                                <div>Teilnehmer</div>
                            </div>
                        </div>

                        <div v-for="event in events" :key="event.id">
                            <hr>

                            <div v-if="event.userCreated == currentUser">
                                <div class="grid grid-cols-6 gap-4 my-2">
                                    <div class="h-10 my-3">{{ event.title }}</div>
                                    <div class="h-10 my-3">{{ event.date }}</div>
                                    <div class="h-10 my-3">{{ event.time }}</div>
                                    <div class="h-10 my-3">{{ event.accepted }} von {{ event.total }}</div>
                                    <button @click="updateEvent(event.id)"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold h-10 px-5 my-2 rounded text-center">Bearbeiten</button>
                                    <button
                                        class="hover:bg-red-500 hover:text-white text-black font-bold h-10 px-5 m-2 rounded text-center"
                                        @click="deleteEvent(event.id)">Löschen</button>
                                </div>
                            </div>

                            <div v-if="event.userCreated != currentUser">
                                <div class="grid grid-cols-6 gap-4 my-2">
                                    <div>{{ event.title }}</div>
                                    <div>{{ event.date }}</div>
                                    <div>{{ event.time }}</div>
                                    <div>{{ event.accepted }} von {{ event.total }}</div>
                                    <button @click="viewEvent(event.id)"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold h-10 px-5 my-2 rounded text-center">Details</button>
                                    <button
                                        class="hover:bg-red-500 hover:text-white text-black font-bold h-10 px-5 m-2 rounded opacity-50 cursor-not-allowed text-center">Löschen</button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div>
                    
                        <Calendar color="red" :attributes="calendar_events" />

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
import { Calendar } from 'v-calendar';
import 'v-calendar/dist/style.css';


export default defineComponent({
    components: {
        AppLayout,
        JetButton,
        JetInput,
        Calendar
    },
    data() {
        return {
            events: [],
            currentUser: {},
            users: [],
            date: new Date(),
            calendar_events: [],
            invites: []
        }
    },
    created() {
        try {
            axios
                .get('/api/events/')
                .then(response => {
                    
                    axios.get("/api/invites").then((response) => {
                        this.invites = response.data;
                        console.log(this.invites)
                    })
                    
                    this.events = response.data;
                    
                    axios.get("/api/users").then((response) => {
                        this.users = response.data;

                        var eventsLength = this.events.length;
                        var usersLength = this.users.length;
                        
                        //Mark current date red
                        let event_data = { key: 'Any', highlight: { color: 'red', fillMode: 'outline' }, dates: new Date() }
                        this.calendar_events.push(event_data)

                        for (var i = 0; i < eventsLength; i++) {

                            let event_data = { key: 'Any', highlight: true, dates: this.events[i].date, popover: { label: this.events[i].title, visibility: 'hover' } }
                            this.calendar_events.push(event_data)

                            let acceptedUser = 0;
                            let totalUser = 0;

                            for (let invite in this.invites) {
                                if (this.invites[invite].eventID == this.events[i].id) {
                                    if (this.invites[invite].status == 'creator' || this.invites[invite].status == 'accepted') {
                                        acceptedUser++;
                                        totalUser++;
                                    }
                                    else {
                                        totalUser++;
                                    }
                                }
                            }


                            //Teilnehmeranzahl
                            this.events[i].accepted = acceptedUser;
                            this.events[i].total = totalUser;

                            //Zeit nach ISO
                            let refactoredTime = new Date(this.events[i].date + " "+ this.events[i].time);
                            
                            refactoredTime = refactoredTime.toLocaleTimeString("de-DE", {
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            this.events[i].time = refactoredTime;

                            //Datum nach ISO
                            let refactoredDate = new Date(this.events[i].date)

                            refactoredDate = refactoredDate.toLocaleDateString("de-DE", {
                                    year: "numeric",
                                    month: "numeric",
                                    day: "numeric",
                                });

                            this.events[i].date = refactoredDate;


                            //Creator als Klartext
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
        },
        viewEvent(id) {
            window.location.href = `/bbq/view/` + id
        }
    }
})
</script>