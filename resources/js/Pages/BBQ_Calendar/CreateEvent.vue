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
          <!--Mittig platzieren-->
          <form @submit.prevent="addEvent">
            <div class="max-w-xl mx-auto py-12 divide-y md:max-w-4xl">
              <h2 class="text-2xl font-bold">
                Ein neues Event erstellen
              </h2>

              <div class="mt-8 max-w-md">
                <div class="grid grid-cols-1 gap-6">
                  <div></div>

                  <label class="block">
                    <span class="text-gray-700">Titel</span>
                    <input id="title" type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                      placeholder="Grillabend" v-model="event.title" />
                  </label>

                  <label class="block">
                    <span class="text-gray-700">Datum</span>
                    <input id="date" type="date"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                      data-ddg-inputtype="unknown" v-model="event.date" />
                  </label>

                  <label class="block">
                    <span class="text-gray-700">Uhrzeit</span>
                    <input id="time" type="time"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                      data-ddg-inputtype="unknown" v-model="event.time" />
                  </label>

                  <label class="block">
                    <span class="text-gray-700">Standort</span>
                    <input id="location" type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                      placeholder="Max' Hütte" v-model="event.location" />
                  </label>

                  <!--Invites-->

                  <div class="block">
                    <div class="mt-2">
                      <div id="invites">
                      <span class="text-gray-700">Wer soll Eingeladen
                        werden?</span>

                      <div class="mt-2"></div>

                      <template v-for="user in users" :key="user.id">
                        <div v-if="creator != user.id">
                          <label class="inline-flex items-center">
                            <input type="checkbox"
                              class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50"
                              :id="user.id" :value="user" v-model="
                                  checkedUsers
                              " />
                            <span for="user.id" class="ml-2">{{
                            user.name
                            }}</span>
                          </label>
                        </div>
                        <div v-if="creator == user.id">
                          <label class="inline-flex items-center">
                            <input type="checkbox"
                              class="rounded border-gray-300 text-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50"
                              disabled="true" checked="" />
                            <span for="user.id" class="ml-2">{{
                            user.name
                            }}</span>
                          </label>
                        </div>
                      </template>

                    </div>

                      <br />

                      <jet-button type="submit" class="btn btn-primary" style="margin-right: 5%">Erstellen</jet-button>

                      <button @click="back()">
                        Abbrechen
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";

export default defineComponent({
  components: {
    AppLayout,
    JetButton,
    JetInput,
  },
  data() {
    return {
      event: {},
      users: [],
      creator: {},
      checkedUsers: [],
    };
  },
  created() {
    axios.get("/api/user").then((response) => {
      this.creator = response.data.id;
      this.event.userCreated = this.creator;
    });

    axios.get("/api/users").then((response) => {
      this.users = response.data;
    });
  },
  methods: {
    addEvent() {

      if (this.checkedUsers.length > 0 && this.event.title != undefined && this.event.location != undefined && this.event.time != undefined && this.event.date != undefined){
      let passValue;

      axios
        .post("/api/events", this.event)
        .then(
          (response) => (
            (passValue = this.checkedUsers.map((x) => {
              x.eventID = response.data;
              return x;
            })),
            axios
              .post("/api/invites", passValue)
              .then(
                (response) =>
                  (window.location.href = "/bbq/all")
              )

            //(window.location.href = "/bbq/all")
          )
        )
        .catch((err) => console.log(err))
        .finally(() => (this.loading = false));

        //Error handling
          } else {


            if (this.event.title == undefined) {
              document.getElementById("title").classList.add("border-2");
              document.getElementById("title").classList.add("border-rose-600");
            } else if (this.event.title != undefined) {
              try {
                document.getElementById("title").classList.remove("border-2");
                document.getElementById("title").classList.remove("border-rose-600");
              } catch (err) {

              }
            }

            if (this.event.date == undefined) {
              document.getElementById("date").classList.add("border-2");
              document.getElementById("date").classList.add("border-rose-600");
            } else if (this.event.date != undefined) {
              try {
                document.getElementById("date").classList.remove("border-2");
                document.getElementById("date").classList.remove("border-rose-600");
              } catch (err) {

              }
            }

            if (this.event.time == undefined) {
              document.getElementById("time").classList.add("border-2");
              document.getElementById("time").classList.add("border-rose-600");
            } else if (this.event.time != undefined) {
              try {
                document.getElementById("time").classList.remove("border-2");
                document.getElementById("time").classList.remove("border-rose-600");
              } catch (err) {

              }
            }

            if (this.event.location == undefined) {
              document.getElementById("location").classList.add("border-2");
              document.getElementById("location").classList.add("border-rose-600");
            } else if (this.event.location != undefined) {
              try {
                document.getElementById("location").classList.remove("border-2");
                document.getElementById("location").classList.remove("border-rose-600");
              } catch (err) {

              }
            }

            

            if (this.checkedUsers.length < 1) {
              document.getElementById("invites").classList.add("border-2");
              document.getElementById("invites").classList.add("border-rose-600");
            } else if (this.event.title > 0) {
              try {
                document.getElementById("invites").classList.remove("border-2");
                document.getElementById("invites").classList.remove("border-rose-600");
              } catch (err) {

              }
            }

          }
    },
    back() {
      window.location.href = "/bbq/all";
    },
  },
});
</script>
