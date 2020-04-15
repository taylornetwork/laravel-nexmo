<template>
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label>From</label>
                <input class="form-control" type="text" readonly disabled v-model="from" name="from">
            </div>
            <div class="form-group">
                <label>To</label>
                <input class="form-control" type="text" v-model="to" name="to" :disabled="sending">
            </div>
            <div class="form-group">
                <label>Text</label>
                <textarea class="form-control" v-model="text" rows="5" :disabled="sending"></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-success" :disabled="!valid || sending" @click="send()">{{ sending ? 'Sending...' : 'Send' }}</button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        created() {
            axios.get('/api/nexmo/getNumber').then(response => {
                 this.from = response.data;
            });
        },

        data() {
            return {
                to: '',
                text: '',
                from: '',
                sending: false,
            }
        },

        computed: {
            valid() {
                return this.from.length > 0 && this.to.length > 0 && this.text.length > 0;
            }
        },

        methods: {
            send() {
                this.sending = true;
                axios.post('/api/nexmo/sms/outbound', {
                    to: this.to,
                    from: this.from,
                    text: this.text,
                }).then(response => {
                    this.text = '';
                    this.sending = false;
                });
            },
        },
    }
</script>