<template>
	<div :id="'reply-' + id" class="card mt-2">
		<div class="card-header bg-white">
			<div class="level">
				<h5 class="flex"><a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a >
					said <span v-text="ago"></span>...</h5>

				<div v-if="isBest" class="mr-2 text-success">
					<i class="fa fa-star"></i>
				</div>
				<div v-if="signedIn">
					<favorite :reply="reply"></favorite>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div v-if="editing">
				<form @submit.prevent="update" >
					<div class="form-group">
						<wysiwyg v-model="body"></wysiwyg>
					</div>

					<button class="btn btn-primary btn-sm">Update</button >
					<button class="btn btn-link btn-sm" @click="editing = false" type="button">Cancel</button >
				</form >
			</div>
			<div v-else v-html="body"></div>
		</div>
		<div class="card-footer bg-white level" v-if="authorize( 'owns', reply ) || authorize( 'owns', reply.thread )">
			<div v-if="authorize( 'owns', reply )">
				<button class="btn btn-warning btn-sm mr-2" @click="editing = true" >Edit</button >
				<button class="btn btn-danger btn-sm mr-2" @click="destroy" >Delete</button >
			</div>

			<button class="btn btn-success btn-sm ml-auto" @click="markBestReply" v-if="authorize( 'owns', reply.thread ) && !isBest">Best Reply?</button >
		</div>
	</div>
</template>
<script>
	import Favorite from './Favorite.vue';
	import moment from 'moment';

    export default
    {
        props: [ 'reply' ],
	    components: { Favorite },
        data()
        {
            return {
				editing: false,
	            id: this.reply.id,
	            body: this.reply.body,
	            isBest: this.reply.isBest,
            }
        },
	    computed:
	    {
		    ago()
		    {
		        return moment( this.reply.created_at ).fromNow();
		    }
	    },
	    created()
	    {
	        window.events.$on( 'best-reply-selected', id =>
	        {
	            this.isBest = ( id === this.id );
	        } );
	    },
        methods:
        {
            update()
            {
                axios.patch('/replies/' + this.id, {
                    body: this.body
                })
                .catch( error =>
                {
                    flash( error.response.data, 'danger' );
                } );

                this.editing = false;

                flash('updated!');
            },
            destroy()
            {
				axios.delete( '/replies/' + this.id );

				this.$emit('deleted', this.id);
            },
            markBestReply()
            {
                this.isBest = true;

                axios.post( '/replies/' + this.id + '/best' );

                window.events.$emit( 'best-reply-selected', this.id );
            }
        },
    }
</script>