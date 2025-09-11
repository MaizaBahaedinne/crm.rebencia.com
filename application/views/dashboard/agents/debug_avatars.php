<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Debug Avatars Agents</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Agent</th>
                                            <th>Email</th>
                                            <th>Agent Avatar (DB)</th>
                                            <th>Avatar Helper</th>
                                            <th>Preview</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($agents)): ?>
                                            <?php foreach($agents as $agent): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($agent->agent_name); ?></td>
                                                    <td><?php echo htmlspecialchars($agent->agent_email); ?></td>
                                                    <td>
                                                        <small><?php echo htmlspecialchars($agent->agent_avatar ?? 'NULL'); ?></small>
                                                    </td>
                                                    <td>
                                                        <?php $avatar_url = get_agent_avatar_url($agent); ?>
                                                        <small><?php echo htmlspecialchars($avatar_url); ?></small>
                                                    </td>
                                                    <td>
                                                        <img src="<?php echo $avatar_url; ?>" 
                                                             alt="Avatar" 
                                                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;"
                                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                                        <span style="display: none; color: red;">❌ Erreur</span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Aucun agent trouvé</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
