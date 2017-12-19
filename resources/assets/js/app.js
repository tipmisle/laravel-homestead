
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('jquery');
require('moment');
require('fullcalendar');
require('axios');

$("#f").on('submit', function(e) {
	e.preventDefault();
	var date = $("#date").text();
	var summary = $("#summary").val();
	var time_s = $("#time_s").val();
	var time_e = $("#time_e").val();
	var calendar_id = $("#calendar_id").val();
	console.log(calendar_id);
	axios.post('/event/create', {
		date: date,
		summary: summary,
		time_s: time_s,
		time_e: time_e,
		calendarid: calendar_id
	})
		.then(function (response) {
		$('#exampleModal').modal('hide');
	})
		.catch(function (error) {
		console.log(error);
	});
});

$("#a").on('submit', function(e) {
	e.preventDefault();
	var date = $("#date").text();
	var summary = $("#summary").val();
	var time_s = $("#time_s").val();
	var time_e = $("#time_e").val();
	var calendar_id = $("#calendar_id").val();
	var user_id = $("#user_id").text();
	var url = '/event/create/profile/' + user_id;
	axios.post(url, {
		date: date,
		summary: summary,
		time_s: time_s,
		time_e: time_e,
		calendarid: calendar_id,
		userid: user_id
	})
		.then(function (response) {
		$('#exampleModal').modal('hide');
	})
		.catch(function (error) {
		console.log(error);
	});
});

$("#c").on('submit', function(e) {
	e.preventDefault();
	var title = $("#title").val();
	console.log(calendar_id);
	axios.post('/calendar/create', {
		title: title
	})
		.then(function (response) {
		$('#createCalendarModal').modal('hide');
	})
		.catch(function (error) {
		console.log(error);
	});
});