<template>
	<div>
		<div class="level">
			<img :src="avatar" width="50" height="50" alt="profile image" class="mr-2">

			<h1>
				{{ user.name }}
				<small>
					{{ reputation }}
				</small>
			</h1>
		</div>

		<form v-if="canUpdate">
			<image-upload name="avatar" @loaded="onLoad"></image-upload>
		</form >
	</div>
</template>
<script >
	import ImageUpload from './ImageUpload.vue';

	export default
	{
	    props: [ 'user' ],
		components:
		{
		    ImageUpload
		},
		data()
		{
		    return {
                avatar: this.user.avatar_path
		    }
		},
		computed:
		{
		    canUpdate: function()
		    {
		        return this.authorize( user => user.id === this.user.id );
		    },
			reputation()
			{
			    return this.user.reputation + "XP";
			}
		},
		methods:
		{
		    onLoad( avatar )
		    {
		        this.avatar = avatar.src;
		        this.persist( avatar.file );
		    },
			persist( avatar )
			{
			    var data = new FormData();

			    data.append( 'avatar', avatar );

			    axios.post( '/api/users/${this.user.name}/avatar', data ).then( () => flash('Avatar uploaded!') );
			}
		}
	}
</script >