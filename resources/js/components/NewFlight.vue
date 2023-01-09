<template>
    <div class="container" v-if="add_new_row">
        <div class="card">
            <div class="card-header">
                Sukurti naują skrydį
                <div class="float-end">
                    <span @click="unset_add_new" class="fa fa-minus"></span>
                </div>
            </div>
            <div class="card-body">

                <div class="row form-row my-1">
                    <div class="col-12 col-sm-4">
                        <input type="datetime-local" id="departure_time" class="form-control"  placeholder="Išvykimo laikas" v-model="new_row.departure_time">
                    </div>
                    <div class="col-12 col-sm-4">
                        <input type="text" id="departure_tz" class="form-control" readonly placeholder="Išvykimo laiko zona" v-model="new_row.departure_tz">
                    </div>
                    <div class="col-12 col-sm-4">
                        <select class="form-control" id="departure_airport" @change="select_departure_airport" name="" v-model="new_row.departure_airport">
                            <option value="">Išvykimo orouostas ...</option>
                            <option v-for="a in airports" :value="a.id">{{ a.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row form-row my-1">
                    <div class="col-12 col-sm-4">
                        <input type="datetime-local" id="arrival_time" class="form-control"  placeholder="Išvykimo laikas" v-model="new_row.arrival_time">
                    </div>
                    <div class="col-12 col-sm-4">
                        <input type="text" id="arival_tz" class="form-control" readonly placeholder="Atvykimo laiko zona" v-model="new_row.arrival_tz">
                    </div>
                    <div class="col-12 col-sm-4">
                        <select class="form-control" id="arrival_airport" @change="select_arrival_airport"  name="" v-model="new_row.arrival_airport">
                            <option value="">Atvykimo orouostas ...</option>
                            <option v-for="a in airports" :value="a.id">{{ a.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row my-1">
                    <div class="col">

                        <input type="text" id="available_seats" class="form-control" placeholder="Vietų skaičius" v-model="new_row.available_seats">

                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="col ">
                    <button @click="store_flight" class="btn btn-outline-success ml-auto" :class="{ disabled: disabled_store_btn }" :disabled="disabled_store_btn">Įrašyti</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NewFlight",
        props: [
            'add_new_row'
        ],
        data: function () {
            return {
                new_row: {
                    departure_time: '',
                    departure_tz: '',
                    departure_airport: '',
                    arrival_time: '',
                    arrival_tz: '',
                    arrival_airport: '',
                    available_seats: null
                },
                airports:'',
                disabled_store_btn: false
            }
        },

        methods: {
            store_flight() {
                let got_err = 0;
                this.disabled_store_btn = true;


                // check for empty fields
                if (this.new_row.departure_time === '') {
                    $('#departure_time').addClass('is-invalid');
                    this.disabled_store_btn = false;
                    got_err = 1;
                }
                if (this.new_row.departure_airport === '') {
                    $('#departure_airport').addClass('is-invalid');
                    this.disabled_store_btn = false;
                    got_err = 1;
                }

                if (this.new_row.arrival_time === '') {
                    $('#arrival_time').addClass('is-invalid');
                    this.disabled_store_btn = false;
                    got_err = 1;
                }
                if (this.new_row.arrival_airport === '') {
                    $('#arrival_airport').addClass('is-invalid');
                    this.disabled_store_btn = false;
                    got_err = 1;
                }

                if (this.new_row.available_seats < 1) {
                    $('#available_seats').addClass('is-invalid');
                    this.disabled_store_btn = false;
                    got_err = 1;
                }

                if (got_err === 1) {
                    this.disabled_store_btn = false;
                    return;
                }

                axios.post('store_flight', this.new_row).then(res => {
                    console.log(res.data);
                    window.location.reload();
                })
            },

            select_departure_airport(e){
                this.new_row.departure_tz = this.airports.find(x=>x.id === this.new_row.departure_airport).tz;
            },

            select_arrival_airport(e){
                this.new_row.arrival_tz = this.airports.find(x=>x.id === this.new_row.arrival_airport).tz;
            },
            unset_add_new() {
                this.$parent.add_new = false;
            }

        },
        mounted() {
            axios.get('/get_airports').then(res => {
                this.airports = res.data;
            })
        }
    }
</script>

<style scoped>

</style>