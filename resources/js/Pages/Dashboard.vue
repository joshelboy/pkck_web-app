<template>
    <div class="flex h-screen">
        <div class="m-auto text-center items-center">
            <p class="text">Was willst du machen?</p>
            <jet-button @click="strava" class="button my-3 w-full bg-sky-600 hover:bg-sky-700">Strava authentication</jet-button>
            <div>oder</div>
            <jet-button class="button my-3 w-full bg-sky-600 hover:bg-sky-700 cursor-not-allowed">Command Creator GUI</jet-button>
        </div>
    </div> 
</template>

<script>
import JetButton from '@/Jetstream/Button.vue'
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Welcome from '@/Jetstream/Welcome.vue'

export default defineComponent({
  components: {
      JetButton,
      AppLayout,
      Welcome
  },
  methods: {
            strava(){
                window.location.href = 'https://www.strava.com/oauth/authorize?client_id=68240&response_type=code&redirect_uri=http://192.168.178.30:8000/auth/strava/callback&approval_prompt=force'
            }
        }

});

</script>
<style>
.button {
  margin: 2.5%;
  font-size: 20px;
}
.text{
    font-size: 30px;
    font-weight: bold;
}
</style>