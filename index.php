<?php
/**
 * @package     AppTheme
 * @subpackage  Templates.apptheme
 *
 * @copyright   Copyright (C) 2005 - 2012 Pixel Praise LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// If Joomla 3
if (version_compare(JVERSION, '3', 'ge')) {
	// Add JavaScript Frameworks
	JHtml::_('bootstrap.framework');
	// Load optional rtl Bootstrap css and Bootstrap bugfixes
	JHtmlBootstrap::loadCss($includeMaincss = false, $this->direction);
}

// Add Stylesheets
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');

// Add current user information
$user = JFactory::getUser();

// Adjusting content width
if ($this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span6";
}
elseif ($this->countModules('position-7') && !$this->countModules('position-8'))
{
	$span = "span9";
}
elseif (!$this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span9";
}
else
{
	$span = "span12";
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="'. JURI::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';
}
else if ($this->params->get('theme') == "carbon" || $this->params->get('theme') == "sepia" || $this->params->get('theme') == "ironman" || $this->params->get('theme') == "masterchief")
{
	$logo = '<img src="'. JURI::root() .'templates/' .$this->template. '/images/logo-inverse.png" alt="'. $sitename .'" />';
}
else
{
	$logo = '<img src="'. JURI::root() .'templates/' .$this->template. '/images/logo.png" alt="'. $sitename .'" />';
}

// Register component route helper classes
$pid = (int) $app->getUserState('com_projectfork.project.active.id');

if (jimport('projectfork.library')) {
    $components = array(
        'com_pfprojects',
        'com_pfmilestones',
        'com_pftasks',
        'com_pftime',
        'com_pfrepo',
        'com_pfforum'
    );

    foreach ($components AS $component)
    {
        $route_helper = JPATH_SITE . '/components/' . $component . '/helpers/route.php';
        $class_name   = 'PF' . str_replace('com_pf', '', $component) . 'HelperRoute';

        if (file_exists($route_helper)) {
            JLoader::register($class_name, $route_helper);
        }
    }
}

// Have to find the project repo base dir
if ($pid) {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query->select('attribs')
          ->from('#__pf_projects')
          ->where('id = ' . $db->quote($pid));

    $db->setQuery($query);
    $project_attribs = $db->loadResult();

    $project_params = new JRegistry;
    $project_params->loadString($project_attribs);

    $repo_dir = (int) $project_params->get('repo_dir');
}
else {
    $repo_dir = 1;
}

// Prepare component base links
$link_tasks    = (class_exists('PFtasksHelperRoute') ? PFtasksHelperRoute::getTasksRoute() : 'index.php?option=com_pftasks');
$link_projects = (class_exists('PFprojectsHelperRoute') ? PFprojectsHelperRoute::getProjectsRoute() : 'index.php?option=com_pfprojects');
$link_time     = (class_exists('PFtimeHelperRoute') ? PFtimeHelperRoute::getTimesheetRoute() : 'index.php?option=com_pftime');
$link_ms       = (class_exists('PFmilestonesHelperRoute') ? PFmilestonesHelperRoute::getMilestonesRoute() : 'index.php?option=com_pfmilestones');
$link_forum    = (class_exists('PFforumHelperRoute') ? PFforumHelperRoute::getTopicsRoute() : 'index.php?option=com_pfforum');
$link_repo     = (class_exists('PFrepoHelperRoute') ? PFrepoHelperRoute::getRepositoryRoute($pid, $repo_dir) : 'index.php?option=com_pfrepo&filter_project=' . $pid . '&parent_id=' . $repo_dir);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<jdoc:include type="head" />
	<?php if (version_compare(JVERSION, '3', 'ge')) : ?>
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
	<?php else : ?>
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/html5.js"></script>
	<![endif]-->
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery-noconflict.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/bootstrap.min.js"></script>
		<link href='<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/icomoon.css' rel='stylesheet' type='text/css'>
	<?php endif; ?>
	<?php if ($this->params->get('theme') == "dribbble"): ?>
		<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
	<?php endif; ?>
	<?php if ($this->params->get('theme') == "masterchief"): ?>
		<link href='http://fonts.googleapis.com/css?family=Share+Tech' rel='stylesheet' type='text/css'>
	<?php endif; ?>
</head>

<body class="<?php echo $this->params->get('theme', 'mac'); ?> site <?php echo $option . " view-" . $view . " layout-" . $layout . " task-" . $task . " itemid-" . $itemid . " ";?>">
	<!-- Body -->
	<div class="body">
		<div class="container-fixed">
			<div class="navigation">
				<div class="header-search pull-right">
					<jdoc:include type="modules" name="position-0" style="none" />
				</div>
				<a class="brand pull-left" href="<?php echo $this->baseurl; ?>">
						<?php echo $logo;?> 
					</a>
				<jdoc:include type="modules" name="position-1" style="none" />
			</div>
			<div class="row-fixed">
				<?php if ($this->countModules('position-7')): ?>
				<!-- Begin Sidebar -->
				<div id="sidebar" class="sidebar">
					<a class="btn btn-small btn-inverse pull-right sidebar-toggle visible-phone" href="#"><span aria-hidden="true" class="icon-remove"></span></a>
					<div class="sidebar-nav tablet-nav">
						<h3><?php echo JHtml::_('string.truncate', $sitename, 20, false, false);?></h3>
						<jdoc:include type="modules" name="position-1" style="none" />
					</div>
					<div class="sidebar-nav">
						<jdoc:include type="modules" name="position-7" style="xhtml" />
					</div>
				</div>
				<!-- End Sidebar -->
				<?php endif; ?>
				<div id="content" class="content">
					<div class="page-title center">
						<a class="btn <?php if ($this->params->get('theme') == "carbon" || $this->params->get('theme') == "sepia" || $this->params->get('theme') == "ironman" || $this->params->get('theme') == "masterchief") { echo "btn-inverse"; } ?> pull-left sidebar-toggle visible-phone" href="#"><span aria-hidden="true" class="icon-list-view"></span></a>
							<?php if ($user->id) : ?>
							<div class="btn-group pull-right title-nav">
								<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span aria-hidden="true" class="icon-pencil"></span></a>
								<?php if ($this->countModules('create-nav')) : ?>
									<jdoc:include type="modules" name="create-nav" style="none" />
								<?php else: ?>
									<ul class="dropdown-menu">
									<?php
									if($user->authorise('core.create', 'com_pfprojects')) :
									?>
									<li><a href="<?php echo JRoute::_($link_projects . '&task=form.add');?>"><i class="icon-briefcase"></i> <?php echo JText::_('TPL_APPTHEME_NEW_PROJECT');?></a></li>
									<?php
									endif;
									if($user->authorise('core.create', 'com_pfmilestones')) :
									?>
									<li><a href="<?php echo JRoute::_($link_ms . '&task=form.add');?>"><i class="icon-flag"></i> <?php echo JText::_('TPL_APPTHEME_NEW_MILESTONE');?></a></li>
									<?php
									endif;
									if($user->authorise('core.create', 'com_pftasks')) :
									?>
									<li><a href="<?php echo JRoute::_($link_tasks . '&task=tasklistform.add');?>"><i class="icon-list-view"></i> <?php echo JText::_('TPL_APPTHEME_NEW_TASKLIST');?></a></li>
									<?php
									endif;
									if($user->authorise('core.create', 'com_pftasks')) :
									?>
									<li><a href="<?php echo JRoute::_($link_tasks . '&task=taskform.add');?>"><i class="icon-checkbox"></i> <?php echo JText::_('TPL_APPTHEME_NEW_TASK');?></a></li>
									<?php
									endif;
									if($user->authorise('core.create', 'com_pftime')) :
									?>
									<li><a href="<?php echo JRoute::_($link_time . '&task=form.add');?>"><i class="icon-clock"></i> <?php echo JText::_('TPL_APPTHEME_NEW_TIME');?></a></li>
									<?php
									endif;
									if($user->authorise('core.create', 'com_pfforum')) :
									?>
									<li><a href="<?php echo JRoute::_($link_forum . '&task=topicform.add');?>"><i class="icon-comments-2"></i> <?php echo JText::_('TPL_APPTHEME_NEW_TOPIC');?></a></li>
									<?php
									endif;
									if($user->authorise('core.create', 'com_pfrepo') && $app->getUserState('com_projectfork.project.active.id')) :
									?>
									<li><a href="<?php echo JRoute::_($link_repo . '&task=fileform.add');?>"><i class="icon-upload"></i> <?php echo JText::_('TPL_APPTHEME_NEW_FILE');?></a></li>
									<?php
									endif;
									?>
									</ul>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						<?php if ($this->countModules('title-nav')) : ?>
							<div class="btn-group pull-left title-nav visible-desktop">
								<a class="btn <?php if ($this->params->get('theme') == "carbon" || $this->params->get('theme') == "ironman" || $this->params->get('theme') == "masterchief") { echo "btn-inverse"; } ?> dropdown-toggle" data-toggle="dropdown" href="#"><span aria-hidden="true" class="icon-list"></span></a>
								<jdoc:include type="modules" name="title-nav" style="none" />
							</div>
						<?php endif; ?>
						<?php echo JHtml::_('string.truncate', $doc->getTitle(), 0, false, false);?>
					</div>
					<div class="content-inner">
						<a name="top"></a>
						<!-- Begin Content -->
						<jdoc:include type="modules" name="position-3" style="xhtml" />
						<jdoc:include type="message" />
						<jdoc:include type="component" />
						<jdoc:include type="modules" name="position-2" style="none" />
						<!-- End Content -->
					</div>
				</div>
				<?php if ($this->countModules('position-8')) : ?>
				<div id="aside" class="aside">
					<!-- Begin Right Sidebar -->
					<jdoc:include type="modules" name="position-8" style="well" />
					<!-- End Right Sidebar -->
				</div>
				<?php endif; ?>
			</div>
			<div class="footer">
				<jdoc:include type="modules" name="footer" style="none" />
				<ul class="nav nav-pills pull-right">
					<li><a href="#top" id="back-top"><?php echo JText::_('TPL_APPTHEME_BACKTOTOP'); ?></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	!function ($) {
		$(function(){
			// Sidebar Toggle
		    $(".sidebar-toggle").click(function(){
		    	$('.sidebar').toggle();
		        return false;
		    });
		    // Sidebar Toggle H3
			$('.sidebar h3').click(function(e) {
			  e.preventDefault();
			  $(this).siblings().toggle("fast");
			});
			// Remove pills class from sidebar position-0
			$(".tablet-nav ul").removeClass("nav-pills");
			// Remove pills class from sidebar title nav
			$(".title-nav ul").removeClass("nav-pills").removeClass("nav").removeClass("menu").addClass("dropdown-menu");
		});
		
		<?php if (version_compare(JVERSION, '3', 'ge')) : ?>
		
		var jPanelMenu = {};
		$(function() {
			$('pre').each(function(i, e) {hljs.highlightBlock(e)});
		
			jPanelMenu = $.jPanelMenu({
				menu: 'header.main nav'
			});
		
			var jR = jRespond([
				{
					label: 'small',
					enter: 0,
					exit: 800
				},{
					label: 'large',
					enter: 800,
					exit: 10000
				}
			]);
		
			jR.addFunc({
				breakpoint: 'small',
				enter: function() {
					jPanelMenu.on();
					$(document).on('click',jPanelMenu.menu + ' li a',function(e){
						if ( jPanelMenu.isOpen() && $(e.target).attr('href').substring(0,1) == '#' ) { jPanelMenu.close(); }
					});
				},
				exit: function() {
					jPanelMenu.off();
					$(document).off('click',jPanelMenu.menu + ' li a');
				}
			});
		});
		<?php endif; ?>
	}(window.jQuery)
	</script>
</body>
</html>
