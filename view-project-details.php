<div class="row">

                        <!-- Basic Information -->

                        <div class="col-md-6">

                            <h4 class="section-title">Basic Information</h4>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Project Name</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['project_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Job No</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['job_no']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Job No Reference Date</div>

                                <div class="col-md-8"><?php echo date('d/m/Y', strtotime($project['job_no_reference_date'])); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Project Type</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['project_type_name']); ?></div>

                            </div>

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Priority</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['priority']); ?></div>

                            </div>
                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Current Department</div>

                                <div class="col-md-8"><?php echo  htmlspecialchars($project['current_dept_name']) ; ?></div>

                            </div>







                            <div class="row info-row">

                                <div class="col-md-4 info-label">Assigned Employees</div>

                                <div class="col-md-8">

                    <div class="assigned-employees">

                        <?php if (!empty($project['assigned_emp_names'])): ?>

                            <?php foreach($project['assigned_emp_names'] as $emp_name): ?>

                                <!-- <span class="badge badge-info mr-2 mb-2"> -->

                                    <!-- <i class="fas fa-user mr-1"></i> -->

                                    <?php echo htmlspecialchars($emp_name); ?>

                                <!-- </span> -->

                            <?php endforeach; ?>

                        <?php else: ?>

                            <p class="text-muted">No employees assigned</p>

                        <?php endif; ?>

                    </div>

                    </div>

                    </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Client Name</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['client_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Description</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['description']); ?></div>

                            </div>

                        </div>

                        

                        <!-- Location & Duration -->

                        <div class="col-md-6">

                            <h4 class="section-title">Location & Duration</h4>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Circle</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['circle_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Division</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['division_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Subdivision</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['sub_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Taluka</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['taluka_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Start Date</div>

                                <div class="col-md-8"><?php echo date('d/m/Y', strtotime($project['start_date'])); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">End Date</div>

                                <div class="col-md-8"><?php echo date('d/m/Y', strtotime($project['end_date'])); ?></div>

                            </div>

                             <!-- add the new filed is probable_date_of_completion but in my respinse is null -->

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Probable Date of Completion</div>

                                <div class="col-md-8"><?php echo date('d/m/Y', strtotime($project['probable_date_of_completion'])); ?></div>

                            </div>

                        </div>

                    </div>

                    

                    <div class="row mt-4">

                        <!-- Financial Information -->

                        <div class="col-md-6">

                            <h4 class="section-title">Financial Information</h4>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Estimated Amount</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['estimated_amount']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Budget Head</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['budget_head']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Length</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['length']); ?> meters</div>

                            </div>

                        </div>

                        

                        <!-- Status & Metadata -->

                        <div class="col-md-6">

                            <h4 class="section-title">Status & Metadata</h4>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Status</div>

                                <div class="col-md-8">

                                    <span class="badge badge-in-progress"><?php echo htmlspecialchars($project['status']); ?></span>

                                </div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Created By</div>

                                <div class="col-md-8"><?php echo $project['created_by'] ? htmlspecialchars($project['creator_name']) : '-'; ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Created At</div>

                                <div class="col-md-8"><?php echo date('d/m/Y H:i', strtotime($project['created_at'])); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Last Updated By</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['updater_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Last Updated At</div>

                                <div class="col-md-8"><?php echo date('d/m/Y H:i', strtotime($project['updated_at'])); ?></div>

                            </div>

                        </div>

                    </div>




