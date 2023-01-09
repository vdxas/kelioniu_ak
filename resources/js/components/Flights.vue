<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-1">Skrydžiai</div>
                            <div class="col-6"></div>
                            <div class="col-3 float-end">
                                <select class="form-control" id="time_zones_select" v-if="!tz" @click="get_time_zones" @change="load_by_tz" v-model="selected_tz">
                                    <option value="">Rodyti pagal laiko juosta ...</option>
                                    <option v-for="(i,tz) in time_zones" :value="i">{{ tz }}</option>
                                </select>
                                <a href="/home" v-if="tz" class="btn btn-outline-info">Rodyti originalų laiką</a>
                            </div>
                            <div class="col-2">
                                <button @click="set_add_new" class="btn btn-outline-success">Naujas skrydis <span class="fa fa-plus"></span></button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <new-flight :add_new_row="add_new"></new-flight>
                        <edit-flight :new_row="flight_to_edit"></edit-flight>

                        <div class="w-100 my-4"></div>

                        <table class="table table-bordered">
                            <tr>
                                <th>Išvykimo laikas</th><th>Išvykimo TZ</th><th>Išvykimo oro uostas</th>
                                <th>Atvykimo laikas</th><th>Atvykimo TZ</th><th>Atvykimo oro uostas</th>
                                <th>Vietų skaičius</th><th></th>
                            </tr>
                            <tbody>
                               <tr v-for="f in flights">
                                   <td v-if="!f._departure_time">{{ f.departure_time }}</td>
                                   <td v-if="f._departure_time" class="text-secondary fst-italic" :title="f.departure_time">{{ f._departure_time }}</td>
                                   <td v-if="!f._departure_tz">{{ f.departure_tz }}</td>
                                   <td v-if="f._departure_tz" class="text-secondary fst-italic" :title="f.departure_tz">{{ f._departure_tz }}</td>
                                   <td>{{ f.departure_airport_data.name }} ({{f.departure_airport_data.iata_code}})</td>
                                   <td v-if="!f._arrival_time">{{ f.arrival_time }}</td>
                                   <td v-if="f._arrival_time" class="text-secondary fst-italic" :title="f.arrival_time">{{ f._arrival_time }}</td>
                                   <td v-if="!f._arrival_tz">{{ f.arrival_tz }}</td>
                                   <td v-if="f._arrival_tz" class="text-secondary fst-italic" :title="f.arrival_time">{{ f._arrival_tz }}</td>
                                   <td>{{ f.arrival_airport_data.name }} ({{f.arrival_airport_data.iata_code}})</td>
                                   <td>{{ f.available_seats }}</td>
                                   <td><span @click="edit_flight(f)" class="fa fa-pencil"></span></td>
                               </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import NewFlight from './NewFlight.vue';
    import EditFlight from './EditFlight.vue';
    export default {
        name: "Flights",
        props: ['tz'],
        components: {
            NewFlight,
            EditFlight,
        },

        data: function () {
            return {
                flights: '',
                time_zones: '',
                flight_to_edit: '',
                add_new: false,
                selected_tz: ''
            }
        },


        methods: {
            edit_flight(f) {
                this.add_new = false;
                this.flight_to_edit = f;
            },

            set_add_new() {
                this.add_new = true;
                this.flight_to_edit = '';
            },

            get_time_zones() {
                axios.get('/get_timezones').then(res => {
                    this.time_zones = res.data;
                })
            },

            load_by_tz(){
                window.location.href = '/home/' + this.selected_tz;
            }
         },

        mounted() {

            axios.get('/get_flights/'+this.tz).then(res => {
                this.flights = res.data;
            })
        }
    }
</script>
