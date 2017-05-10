<?php
$bgs = ['bg-info', 'bg-secondary', 'bg-success', 'bg-warning'];
//print_r(get_defined_vars());exit;
?>
<div class="col-md-12">
    <div class="timeline-centered">
        <?php foreach ($timeline as $event): ?>
            <article class="timeline-entry">

                <div class="timeline-entry-inner">

                    <div class="timeline-icon <?= $bgs[array_rand($bgs)] ?>">
                        <i class="entypo-suitcase"></i>
                    </div>

                    <div class="timeline-label">
                        <h2><?= $event->user->name ?>
                            <small title="<?= $event->user->email ?>"><strong><?= $event->user->username ?></strong>
                            </small>
                        </h2>
                        <p>
                            <small class="text-muted"><i class="glyphicon glyphicon-time"></i>
                                <?= date("l jS F Y h:i:s A", strtotime($event->created_at)) ?>
                            </small>
                        </p>
                        <p><?= $event->description ?></p>
                        <p>
                            <?php
                            if (!empty($event->file)):?>
                                <a href="<?= $event->file->link ?>" download> <i
                                            class="fa fa-paperclip"></i> <?= $event->file->filename ?></a>
                                <?php
                            endif;
                            ?>
                        </p>
                    </div>
                </div>

            </article>
        <?php endforeach; ?>

        <article class="timeline-entry begin">

            <div class="timeline-entry-inner">

                <div class="timeline-icon"
                     style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                    <i class="entypo-flight"></i>
                    <a data-target="#adddocument" href="<?= $url; ?>" data-backdrop="static" data-toggle="modal"
                       class="new-entity add toolsbutton">
                        <img src="<?= RELATIVE_PROJECT_PATH ?>/templates/admin/images/icons/small/add_icon.png"></a>
                </div>

            </div>

        </article>

    </div>

</div>
<link href="<?= RELATIVE_PROJECT_PATH . '/admin/claims/assets/css/timeline.css'; ?>" type="text/css" rel="stylesheet"/>