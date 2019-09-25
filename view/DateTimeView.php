<?php

class DateTimeView {


	public function getWeekDay() {
		return date('l');
	}

	public function getDateDay() {
		return date('d');
	}

	public function getMonth() {
		return date('F');
	}

	public function getYear() {
		return date('Y');
	}

	public function getHour() {
		//date_default_timezone_set('Europe/Stockholm');
		return date('G');
	}

	public function getMinute() {
		return date('i');
	}

	public function getSecond() {
		return date('s');
	}

	public function show() {

		$dateTimeObj = getdate();

		$timeString = '';

		return '<p>' . $timeString . $this->getWeekDay() . ', the ' . $this->getDateDay() . 'th of ' . $this->getMonth() . ' ' . $this->getYear() . ', The time is ' . $this->getHour() . ':' . $this->getMinute() . ':' . $this->getSecond() . '</p>';
	}
}