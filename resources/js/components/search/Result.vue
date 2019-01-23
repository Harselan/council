<template>
	<tr class="border-b">
		<td class="text-sm p-4 border-b"><a :href="data.path" >{{ data.title }}</a ></td>
		<td class="text-sm p-4 border-b">{{ data.created_at }}</td>
	</tr>
</template>

<script >
	export default
	{
		props: [ 'data' ]
	}
</script >