<?php
/**
 * The header for ng-WordPress theme.
 * @package ng-WordPress
 */?><!DOCTYPE html>
<html <?php language_attributes(); ?> ng-app="ngWordpress">
<head>
<!-- angular base tag -->
<base href="/">

<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<!--[if lt IE 8]>
	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<div class="container-fluid">
		<div class="row">
			<div class="header-nav-wrapper">
				<div class="logo">
					<a href="/"><?php bloginfo( 'name' ); ?></a>
				</div>
				<div class="primary-nav-wrapper">
					<nav ng-controller="topMenuCtr">
						<ul class="primary-nav">
							<li ng-repeat="link in topmenu" ng-class="{active: isActive(link)}">
								<a ng-href="{{link.url}}">{{link.title}}</a>
							</li>
						</ul>
					</nav>
					<div class="secondary-nav-wrapper">
						<ul class="secondary-nav">
							<li class="search"><a href="" class="show-search"><i class="fa fa-search"></i></a></li>
						</ul>
					</div>
					<div class="search-wrapper">
						<ul class="search">
							<li>
								<input type="text" id="search-input" placeholder="Start typing then hit enter to search">
							</li>
							<li>
								<a href="" class="hide-search"><i class="fa fa-close"></i></a>
							</li>
						</ul>
					</div>
				</div>
				<div class="navicon">
					<a class="nav-toggle" href=""><span></span></a>
				</div>
			</div>
		</div>
	</div>

