<template>
    <div>
        <div class="row">
            <div class="col">
                <h4>
                    CONTACT
                </h4>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-8">
                <message-feed :contact="selectedContact"></message-feed>
            </div>
            <div class="col-4">
                <contacts></contacts>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <textarea v-model="message.text" class="form-control" placeholder="Enter a message..." rows="3" :disabled="sending" autofocus></textarea>
                </div>
                <button class="btn btn-block btn-success" :disabled="sending || message.text.length === 0" @click="send()">
                    {{ sending ? 'Sending...' : 'Send' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import MessageFeed from "./MessageFeed";
    import Contacts from "./Contacts";

    export default {
        data() {
            return {
                selectedContact: null,
                sending: false,
                message: {
                    text: '',
                    isInbound: false,
                    to: '',
                },
            }
        },

        components: {Contacts, MessageFeed}
    }
</script>
