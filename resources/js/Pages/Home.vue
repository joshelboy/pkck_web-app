<template>
  <div>
    <apexcharts ref="exampleChart" width="500" height="350" type="line" :options="chartOptions" :series="series"></apexcharts>
  </div>
</template>

<script>
import axios from 'axios';
import VueApexCharts from "vue3-apexcharts";

export default {

  components: {
    apexcharts: VueApexCharts,
  },

  data: function() {
    return {
      chartOptions: {
        chart: {
          id: "strava-1y",
        },
        xaxis: {
          type: "datetime",
          categories: [],
        },
        tooltip: {
          x: {
            format: "dd MMM yyyy"
          },
        },
      },
      series: [],
    };
  },

  created: function() {
    this.getData();
    console.log("get");
  },

  methods: {
      async getData() {
        try{
          /*
          var startDate = new Date();
          var endDate = new Date();
          startDate.setDate(startDate.getDate() - 83);

          let loop = new Date(startDate);

          while (loop <= endDate) {
            console.log(loop);
            let newDate = loop.setDate(loop.getDate() + 1);
            loop = new Date(newDate);
          }*/

          const idList = await axios.get('/api/strava_user');
          //console.log(idList);
          if (idList.status == 200){
            let seriesStack = [];
            for (let row of idList.data){
              let dataStack = [];

              const tours = await axios.get('/api/tours/' + row.strava_id);
              if(tours.status == 200){
                
                var startDate = new Date();
                var endDate = new Date();
                startDate.setDate(startDate.getDate() - 83);

                let loop = new Date(startDate);

                while (loop <= endDate) {
                  let dataDummy = { x: loop.toISOString().slice(0, 10), y: '0'}
                  dataStack.push(dataDummy);
                  let newDate = loop.setDate(loop.getDate() + 1);
                  loop = new Date(newDate);
                }

                for(let row of tours.data){

                  /*while (new Date(row.date) < new Date(pointerDate)) {
                    let dataObject = { x: pointerDate.toISOString().slice(0, 10), y: '0'};
                    dataStack.push(dataObject);
                    console.log("Date")
                    let newDate = pointerDate.setDate(pointerDate.getDate() + 1);
                    pointerDate = new Date(newDate);
                  }*/

                  //console.log(row.date);
                  //console.log(new Date(row.date));
                  //console.log(dataStack)
                  for (let stackRow of dataStack) {
                    if (row.date == stackRow.x) {
                      if (row.distance != null && row.distance != undefined) {
                        stackRow.y = row.distance;
                        console.log("changed");
                      }
                    }
                  }

                  /*if (row.distance != undefined && row.date != undefined){
                    let dataObject = { x: row.date, y: row.distance};
                    dataStack.push(dataObject);
                  }*/
                }
              }

              let seriesObject = { name: row.strava_name, data: dataStack }
              seriesStack.push(seriesObject);
              console.log(seriesStack)

              /*this.series = [{
                data: dataStack,
                name: row.strava_name
              }]*/

            }
            this.series = seriesStack;
          }
        }
        catch (err){
          console.log(err);
        }
      },
      async read() {

        let categoriesStack = [];
        let dataStack = [];


        await axios.get('/api/tours').then(function(response) {
          if (response.status == 200){
            for (let row of response.data) {
              if (row.distance != undefined && row.date != undefined){
                categoriesStack.push(row.date);
                dataStack.push(row.distance);
              }
              console.log(dataStack);
            }
          }
          //  console.log(response);
        })
        .catch(function(error){
            console.log(error);
        })

        this.series = [
          {
            name: 'Elias',
            data: dataStack,
          },
        ];

        this.chartOptions = {
            xaxis: {
              categories: categoriesStack,
            }
          };

         //this.$refs.exampleChart.updateOptions({ colors: '#52B12C' })
         this.$refs.exampleChart.appendSeries({ name: 'newSeries', data: [32, 44, 31, 41, 22] })
         
      }
  }
};
</script>

<style>

</style>