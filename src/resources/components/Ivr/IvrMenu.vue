<template>
    <div class="card">

        <div class="card-body text-center text-muted" v-if="loading || !hasMenus()">
            {{ loading ? 'Loading...' : 'No menus found.' }}
            <div v-if="!hasMenus()">
                <hr>
                <button class="btn btn-block btn-outline-secondary" @click="editor = true" :disabled="saving">
                    Add New Menu
                </button>
            </div>
        </div>

        <div v-if="!loading && !editor && hasMenus()">
            <table class="table">
                <thead>
                    <th>Name</th>
                    <th>Slug</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="ivrObj in ivrs">
                        <td>{{ ivrObj.name }}</td>
                        <td>{{ ivrObj.slug }}</td>
                        <td class="text-right">
                            <div class="btn-group">
                                <button class="btn btn-outline-primary" @click="openSteps(ivrObj)" :disabled="editor">Steps</button>
                                <button class="btn btn-outline-secondary" @click="openEditor(ivrObj)" :disabled="editor">Edit</button>
                                <button class="btn btn-outline-danger" :disabled="editor" @click="deleteIvr(ivrObj)">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center">
                            <button class="btn btn-block btn-outline-secondary" @click="editor = true" :disabled="saving">
                                Add New Menu
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="!loading && editor">
            <div class="card-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" v-model="ivr.name">
                </div>

                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" class="form-control" v-model="ivr.slug">
                </div>
            </div>
            <div class="card-footer">
                <div class="btn-group btn-block">
                    <button class="btn btn-outline-danger" @click="closeEditor()" :disabled="saving">Cancel</button>
                    <button class="btn btn-outline-success" @click="save()"
                            :disabled="saving || ivr.slug.length === 0">{{ saving ? 'Saving...' : 'Save' }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: [
            'stepsRoute'
        ],

        data() {
            return {
                ivrs: [],
                loading: false,
                saving: false,
                editor: false,
                ivr: {
                    name: '',
                    slug: '',
                }
            }
        },

        computed: {
            editorMethod() {
                return this.edit ? 'put' : 'post';
            },

            editorUri() {
                return '/api/nexmo/ivr/' + (this.edit ? this.ivr.currentSlug : '');
            },

            edit() {
                return this.ivr.id !== undefined;
            },

            editorName() {
                return this.ivr.name;
            }
        },

        watch: {
            editorName(val, old) {
                if(old != '') {
                    this.ivr.slug = this.ivr.name.replace(/\s+/g, '-').toLowerCase();
                }
            },
        },

        created() {
            this.fetchIvrs();
        },

        methods: {
            fetchIvrs() {
                this.loading = true;
                axios.get('/api/nexmo/ivr').then(response => {
                    this.loading = false;
                    this.ivrs = response.data;
                });
            },

            reset() {
                this.ivr = {
                    name: '',
                    slug: '',
                };
            },

            save() {
                this.saving = true;
                axios({
                    method: this.editorMethod,
                    url: this.editorUri,
                    data: this.ivr
                }).then(response => {
                    this.saving = false;
                    this.closeEditor();
                    this.fetchIvrs();
                });
            },

            openEditor(ivr) {
                this.editor = true;
                this.ivr = ivr;
                this.ivr.currentSlug = ivr.slug;
            },

            closeEditor() {
                this.reset();
                this.editor = false;
            },

            deleteIvr(ivr) {
                this.loading = true;
                axios.delete('/api/nexmo/ivr/' + ivr.slug).then(response => {
                    this.fetchIvrs();
                });
            },

            openSteps(ivr) {
                this.$emit('steps', ivr);
            },

            hasMenus() {
                if(this.ivrs === undefined) {
                    this.fetchIvrs();
                }
                return this.ivrs.length > 0;
            }
        },
    }
</script>