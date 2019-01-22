<template>
	<div>
		<div ref="timeline" class="mt-2">&nbsp;</div>
		<div class="relative w-full max-w-full border-1-4 border-grey">
			<div v-for="(activity, index) in items" :key="activity.id">
				<div class="entry">
					<activity-favorite :activity="activity" v-if="activity.type === 'created_favorite'"/>
					<activity-reply :activity="activity" v-if="activity.type === 'created_reply'"/>
					<activity-thread :activity="activity" v-if="activity.type === 'created_thread'"/>
				</div>
			</div>
		</div>

		<paginator class="list-reset py-2 mb-4" :data-set="dataSet" @changed="fetch"></paginator>
	</div>
</template>

<script >
	export default
	{
	    name: 'Activities',
		props:
		{
		    user: {
		        type: Object,
			    required: true
		    }
		},
		data()
		{
		    return {
		        dataSet: false,
			    items: false
		    };
		},
		created()
		{
		    this.fetch();
		},
		methods:
		{
		    fetch( page )
		    {
		        axios.get( this.url(page) ).then( this.refresh );
		    },
			url( page )
			{
			    let url = "/profiles/" + this.user.username + "/activity";

			    return page ? url + "?page=" + page : url + "?page=1";
			},
			refresh( { data } )
			{
			    this.dataSet = data.activities;
			    this.items = data.activities.data;

			    this.$refs.timeline.scollIntoView;
			},
			lastActivity( index )
			{
			    return ( index === this.items.length -1 );
			}
		}
	}
</script >