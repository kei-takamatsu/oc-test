<div class="grid">
    <?php echo $this->element('project_box/inner/_project_box_img', array('project' => $project)) ?>

    <div class="grid_string">
        <?php echo $this->element('project_box/inner/_project_box_string_head', array('project' => $project)) ?>

        <table style="width:100%;">
            <tr>
                <td style="width:190px;">
                    <?php echo $this->element('project_box/inner/_project_box_footer', array('project' => $project)) ?>
                </td>
                <td style="padding:0 10px">
                    <?php echo $this->element('project/graph', array('project' => $project)) ?>
                </td>
            </tr>
        </table>
    </div>
    <?php if($this->Project->get_backed_per($project) >= 100): ?>
        <div class="project_success"> SUCCESS!</div>
    <?php endif ?>
</div>