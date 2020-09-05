<template>
    <div>
        <crud-layout
            :columns="columns"
            :modelName="modelName"
            :formRecord="form"
            :formComponent="formComponent"
        >
            <div class="col-lg-4" slot="stats">
                <stats-card
                    type="gradient-green"
                    :sub-title=totalSpendThisMonth
                    icon="ni ni-money-coins"
                    class="mb-4 mb-xl-0"
                >
                    
                    <template slot="footer">
                        <span class="text-nowrap">Pengeluaran bulan ini</span><br>
                        <span>{{now.toDateString()}}</span>
                    </template>
                </stats-card>
            </div>
            <template slot="table-rows">
                <tr v-for="(spend, index) in spends" :key="spend.id">
                    <td>
                        {{ index + 1 }}
                    </td>
                    <td>
                        {{ spend.name}}
                    </td>
                    <td>
                        {{ spend.user.name}}
                    </td>
                    <td>
                        {{spend.total | currency}}
                    </td>
                    <td>
                        {{spend.information}}
                    </td>
                    <td>
                        <img :src=" 'http://localhost:8000/img/thumbnail/' + spend.thumbnail" alt="img_thumnail" width="100" @click="show(spend.id)" @mouseover=change :style="cursor">
                    </td>
                    <td>
                        {{spend.created_at}}
                    </td>
                    <td>
                        {{spend.updated_at}}
                    </td>
                    <td>
                        <action-button 
                            :formRecord="form"
                            :record="spend"
                            :modelName="modelName"
                        />
                    </td>
                </tr>
            </template>


        </crud-layout>  
        <template>
            <modal :show.sync="display">
                <img :src="imgs" alt="" width="450" style="margin-top=0">
            </modal>
        </template>      
    </div>
</template>
<script>
import SpendingForm from '../forms/SpendingForm'
import { Form } from 'vform'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    data: () => ({
        columns: [
            '#', 'name', 'Petugas Input', 'Total', 'information', 'Image', 'Created', 'Updated',
        ],
        form: new Form({
            id: '',
            name: '',
            img: '',
            total: '',
            information: ''
        }),
        formComponent: SpendingForm,
        modelName: 'spending',
        imgs:'',
        display: false,
        cursor: '',
        now : new Date()
    }),
    computed: {
        ...mapGetters({
            spends: 'spends',
        }),
        totalSpendThisMonth(){
            const currentMonth = new Date().getMonth + 1
            const currentYear = new Date().getFullYear()
            let total = this.spends.filter(e => {
                const [year, month] = e.created_at.split('-')
                return currentMonth == +month && currentYear == year
            })
            let hasil = 0
            total.forEach(e => {
                hasil += e.total
            })
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(hasil)
        }
    },
    methods: {
        show(id){
            this.display = true
            axios.get('zoom/'+id)
            .then( res => {
                this.imgs = 'http://localhost:8000/img/original/'+res.data.data[0]['img']
            }) 
        },
        change(){
            this.cursor = 'cursor:zoom-in'
        },
    },
    mounted() {
       
        
    }
}
</script>