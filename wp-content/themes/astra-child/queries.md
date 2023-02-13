/*******************************************************************************
// GF & Charts DB Queries

// Fetch all submissions dates
select * FROM `wp_gf_entry_meta` 
WHERE `meta_key` = 'survey_entry_submitted_date' 
ORDER BY `wp_gf_entry_meta`.`meta_value` ASC

// Fetch organization Grand total OSIR score for certain reporting period
SELECT SUM(totalOSIRScore.`meta_value`) AS 'grandTotalOSIRScore' FROM 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'total_osir_score') totalOSIRScore, 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'survey_entry_submitted_date' AND `meta_value` BETWEEN '2021-10-15' AND '2021-10-19') reportingPeriod
WHERE totalOSIRScore.entry_id = reportingPeriod.entry_id AND
reportingPeriod.`form_id` = 18

// Fetch organization number of users submissions for this reporting period
SELECT COUNT(userSubmission.`meta_value`) AS 'numberUserSubmissions' FROM 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'is_survey_entry_submitted_by_user') userSubmission, 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'survey_entry_submitted_date' AND `meta_value` BETWEEN '2021-10-15' AND '2021-10-19') reportingPeriod
WHERE userSubmission.entry_id = reportingPeriod.entry_id AND
userSubmission.`meta_value` = 'yes' AND
reportingPeriod.`form_id` = 18

select * FROM `wp_gf_entry_meta` 
WHERE `meta_key` = 'total_osir_score' 
AND `form_id` = 18
ORDER BY `wp_gf_entry_meta`.`meta_value` ASC

// 

// Reporting period Average scores for certain reporting period
SELECT AVG(scaleScore.`meta_value`) AS 'averageScore' FROM 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'total_resiliency_behaviours_score') scaleScore, 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'survey_entry_submitted_date' AND `meta_value` BETWEEN '2021-10-15' AND '2021-10-19') reportingPeriod
WHERE scaleScore.entry_id = reportingPeriod.entry_id AND
reportingPeriod.`form_id` = 18

SELECT scaleScore.`entry_id` AS 'ID',
scaleScore.`meta_value` AS 'Scale Score' FROM 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'total_resiliency_behaviours_score') scaleScore, 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'survey_entry_submitted_date' AND `meta_value` BETWEEN '2021-10-15' AND '2021-10-19') reportingPeriod 
WHERE scaleScore.entry_id = reportingPeriod.entry_id 
AND reportingPeriod.`form_id` = 18

SELECT * FROM `wp_gf_entry_meta`
WHERE (`meta_key` = 'survey_entry_submitted_date' AND 
        `meta_value` BETWEEN '2021-10-15' AND '2021-10-19')

// Organization report 4 average subscales
SELECT SUM(resiliencysBehavioursScore.`meta_value`) AS 'Grand Total Score',
AVG(resiliencysBehavioursScore.`meta_value`) AS 'Average Total Score',
COUNT(resiliencysBehavioursScore.`meta_value`) AS 'Number submissions'
FROM 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'total_resiliency_behaviours_score') resiliencysBehavioursScore
WHERE `form_id` = 18

SELECT COUNT(*) AS 'Number submissions'
FROM `wp_gf_entry_meta`
WHERE meta_key = 'is_survey_entry_submitted_by_user' AND meta_value = 'yes'
AND `form_id` = 18

//

// Grand total number of all answers
SELECT SUM(totalNumberAnswers.`meta_value`) AS 'grandTotalNumberAnswers' FROM 
(select * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'total_number_of_answers') totalNumberAnswers

//

// OSIR Profile Pie Chart (The overall ratio of people within each vulnerability profile)

SELECT `meta_value` AS OSIR_Profile, COUNT(*) AS Count
FROM `wp_gf_entry_meta`
WHERE `meta_key` = 'osir_profile'
AND `form_id` = 18
GROUP BY `meta_value`

SELECT `wp_gf_entry_meta`.`meta_value` AS OSIR_Profile, COUNT(*) AS Count
FROM `wp_gf_entry_meta`, (select * FROM `wp_gf_entry_meta` 
 WHERE `meta_key` = 'my_gform_id' AND `meta_value` = 10) my_gform_id
 WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'
 AND `wp_gf_entry_meta`.`entry_id` = my_gform_id.`entry_id`
 AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY OSIR_Profile


SELECT AVG(osirProfile.osirProfileCount) AS 'Company Average Score' FROM 
(select COUNT(*) AS osirProfileCount FROM `wp_gf_entry_meta` 
 WHERE `meta_key` = 'osir_profile' GROUP BY `meta_value`) osirProfile

// 

// What is your current vocation? (OSIR Index Score by Department) 

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', 
demographicVocation.`meta_value`
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'demographic_vocation' ) demographicVocation 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'
AND `wp_gf_entry_meta`.`entry_id` = demographicVocation.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY osirProfile, demographicVocation.`meta_value`
ORDER BY osirProfile ASC

// I have good mental health, I am in good physical health, I have no concerns about fatigue
// I have little concern about job burnout, I have little concern about job stress.

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(mentalHealthScore.`meta_value`) AS 'Average mental health outlook score' 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'mental_health_score' ) mentalHealthScore 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = mentalHealthScore.`entry_id` 
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(physicalHealthScore.`meta_value`) AS 'Average physical health outlook score' 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'physical_health_score' ) physicalHealthScore 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = physicalHealthScore.`entry_id` 
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(mentalHealthScore.`meta_value`) AS 'Average mental health outlook score' 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'mental_health_score' ) mentalHealthScore 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = mentalHealthScore.`entry_id` 
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile

//

// General Mental Outlook Score (Coping with Substances)

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(healthAlcoholStress.`meta_value`) AS 'I cope with stress using alcohol' 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'health_alcohol_stress_score' ) healthAlcoholStress 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = healthAlcoholStress.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 18

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(healthCannabisStress.`meta_value`) AS 'I cope with stress using Cannabis' 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'health_cannabis_stress_score' ) healthCannabisStress 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = healthCannabisStress.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 18

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(healthTobaccoStress.`meta_value`) AS 'I cope with stress using tobacco' 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'health_tobacco_stress_score' ) healthTobaccoStress 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = healthTobaccoStress.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 18


SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(outlookScore.`meta_value`) AS 'Outlook Score Average' 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'total_outlook_score' ) outlookScore 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = outlookScore.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile

SELECT AVG(mentalOutlookScoreAvg.outlookScoreAverage) as "Company Score Average"
FROM 
(
SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(outlookScore.`meta_value`) AS 'outlookScoreAverage' 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'total_outlook_score' ) outlookScore 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = outlookScore.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile
) AS mentalOutlookScoreAvg

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'Count', SUM(outlookScore.`meta_value`) AS outlookScoreProfile, AVG(outlookScore.`meta_value`) AS outlookScoreProfileAvg 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'total_outlook_score' ) outlookScore 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = outlookScore.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile

SELECT `wp_gf_entry_meta`.`entry_id`, `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
outlookScore.`meta_value` AS outlookScore 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'total_outlook_score' ) outlookScore 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = outlookScore.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 18


//

// How many total years of service have you spent as a First Responder (in all applicable roles)?
// Average OSIR Index score by years of service

SELECT osirYearsOfService.`meta_value` AS 'yearsOfService', 
AVG(`wp_gf_entry_meta`.`meta_value`) AS 'averageOSIRScore' 
FROM `wp_gf_entry_meta`, 
(SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'osir_years_of_service') osirYearsOfService 
WHERE `wp_gf_entry_meta`.`meta_key` = 'total_osir_score' 
AND `wp_gf_entry_meta`.`entry_id` = osirYearsOfService.`entry_id` 
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY osirYearsOfService.`meta_value` 
ORDER BY LENGTH(osirYearsOfService.`meta_value`) ASC, osirYearsOfService.`meta_value` ASC

SELECT osirYearsOfService.`meta_value` AS 'yearsOfService', Count(*), 
SUM(`wp_gf_entry_meta`.`meta_value`) AS 'Total OSIR Index score', 
AVG(`wp_gf_entry_meta`.`meta_value`) AS 'Average OSIR Index score'
FROM `wp_gf_entry_meta`, 
(SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'osir_years_of_service') osirYearsOfService 
WHERE `wp_gf_entry_meta`.`meta_key` = 'total_osir_score' 
AND `wp_gf_entry_meta`.`entry_id` = osirYearsOfService.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY osirYearsOfService.`meta_value` 
ORDER BY LENGTH(osirYearsOfService.`meta_value`) ASC, osirYearsOfService.`meta_value` ASC

//

// 1.	Attendance – number of days missed work due to illness

SELECT `wp_gf_entry_meta`.`entry_id`, 
`wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
absenteeismOSIRProfile.`meta_value` AS 'numberOfMissedDays'
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'absenteeism_osir_profile' ) absenteeismOSIRProfile
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = absenteeismOSIRProfile.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(absenteeismOSIRProfile.`meta_value`) AS 'averageAbsenteeism' 
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'absenteeism_osir_profile' ) absenteeismOSIRProfile 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = absenteeismOSIRProfile.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY osirProfile

SELECT `wp_gf_entry_meta`.`entry_id`, 
`wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
absenteeismOSIRProfile.`meta_value` AS 'Absenteeism',
AVG(absenteeismOSIRProfile.`meta_value`) AS 'averageAbsenteeism'
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'absenteeism_osir_profile' ) absenteeismOSIRProfile
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = absenteeismOSIRProfile.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17

//

// 6.	Trauma/Very stressful situation exposures – Please estimate how many events have you 
// deal with that you have found traumatic/very stressful in the past 12 months 
// Exposure to stress/trauma

// 7 or more (value 7)
// Do not know (value 0)

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(numberTraumaEvents.`meta_value`) AS "Average Number of Trauma Events"
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'number_trauma_events' ) numberTraumaEvents
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = numberTraumaEvents.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY osirProfile

SELECT `wp_gf_entry_meta`.`entry_id`, `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',
count(*),
numberTraumaEvents.`meta_value` AS "Number of Trauma Events",
SUM(numberTraumaEvents.`meta_value`) AS "Total Number of Trauma Events",
AVG(numberTraumaEvents.`meta_value`) AS "Average Number of Trauma Events"
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'number_trauma_events' ) numberTraumaEvents
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = numberTraumaEvents.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY osirProfile

//

// Do you use tobacco products? This includes smoking cigarettes and cigars as well as using chewing tobacco

SELECT employeeByTobaccoUse.`meta_value` AS 'Tobacco use',
count(*) AS 'Number of Employees'
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'tobacco_use' ) employeeByTobaccoUse
WHERE `wp_gf_entry_meta`.`meta_key` = 'is_survey_entry_submitted_by_user' 
AND `wp_gf_entry_meta`.`meta_value` = 'yes'
AND `wp_gf_entry_meta`.`entry_id` = employeeByTobaccoUse.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY employeeByTobaccoUse.`meta_value`

SELECT `wp_gf_entry_meta`.`meta_value` AS 'Is survey submitted', 
count(*) AS 'Number of Employees',
employeeByTobaccoUse.`meta_value` AS 'Tobacco use'
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'tobacco_use' ) employeeByTobaccoUse
WHERE `wp_gf_entry_meta`.`meta_key` = 'is_survey_entry_submitted_by_user' 
AND `wp_gf_entry_meta`.`meta_value` = 'yes'
AND `wp_gf_entry_meta`.`entry_id` = employeeByTobaccoUse.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY employeeByTobaccoUse.`meta_value`

//

// Impact Questions 

// Motivation - On a scale from 1 to 10, on a typical day, I am very motivated at work

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
AVG(impactQuestionsMotivation.`meta_value`) AS "Average per OSIR category"
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'impact_questions_motivation_score' ) impactQuestionsMotivation
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = impactQuestionsMotivation.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile

//

// Short term disability – Over past year, have you been off work for a mental health-related matter?

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
Count(impactQuestionsDisability.`meta_value`) AS "Short Term Disability (Yes)"
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'impact_questions_disability' ) impactQuestionsDisability
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = impactQuestionsDisability.`entry_id`
AND impactQuestionsDisability.`meta_value` = 'Yes'
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
impactQuestionsDisability.`meta_value` AS "Short Term Disability",
Count(impactQuestionsDisability.`meta_value`) AS "Short Term Disability Count"
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'impact_questions_disability' ) impactQuestionsDisability
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = impactQuestionsDisability.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile

SELECT employeeByShorTermDisability.`meta_value` AS 'Short Term Disability',
count(*) AS 'Number of Employees'
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'short_term_disability' ) employeeByShorTermDisability
WHERE `wp_gf_entry_meta`.`meta_key` = 'is_survey_entry_submitted_by_user' 
AND `wp_gf_entry_meta`.`meta_value` = 'yes'
AND `wp_gf_entry_meta`.`entry_id` = employeeByShorTermDisability.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY employeeByShorTermDisability.`meta_value`

//

// 4.	WCC claim – Over the past 12 months, have you made a worker’s compensation claim related /// to an Occupational Stress Injury (such as PTSD and other similar mental illnesses)? 

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
Count(impactQuestionsWCC.`meta_value`) AS "Yes"
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'impact_questions_wcc_claim' ) impactQuestionsWCC
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = impactQuestionsWCC.`entry_id`
AND impactQuestionsWCC.`meta_value` = 'Yes'
AND `wp_gf_entry_meta`.`form_id` = 18
GROUP BY osirProfile

SELECT employeeByWCBClaim.`meta_value` AS 'WCB Claim',
count(*) AS 'Number of Employees'
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'wcb_claim' ) employeeByWCBClaim
WHERE `wp_gf_entry_meta`.`meta_key` = 'is_survey_entry_submitted_by_user' 
AND `wp_gf_entry_meta`.`meta_value` = 'yes'
AND `wp_gf_entry_meta`.`entry_id` = employeeByWCBClaim.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY employeeByWCBClaim.`meta_value`

//

// Have you ever been clinically diagnosed with a mental illness or addictive disorder?
// Number of employees with a mental illness or addictive disorder (Clinical diagnosis)

SELECT employeeByDiagnosedMentalIllness.`meta_value` AS 'Mental Illness Diagnosis',
count(*) AS 'Number of Employees'
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'clinically_diagnosed_mental_illness' ) employeeByDiagnosedMentalIllness
WHERE `wp_gf_entry_meta`.`meta_key` = 'is_survey_entry_submitted_by_user' 
AND `wp_gf_entry_meta`.`meta_value` = 'yes'
AND `wp_gf_entry_meta`.`entry_id` = employeeByDiagnosedMentalIllness.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY employeeByDiagnosedMentalIllness.`meta_value`

SELECT `wp_gf_entry_meta`.`meta_value` AS 'Is survey submitted',
count(*) AS 'Number of Employees',
employeeByDiagnosedMentalIllness.`meta_value` AS 'Mental Illness Diagnosis'
FROM `wp_gf_entry_meta`, 
( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'clinically_diagnosed_mental_illness' ) employeeByDiagnosedMentalIllness
WHERE `wp_gf_entry_meta`.`meta_key` = 'is_survey_entry_submitted_by_user' 
AND `wp_gf_entry_meta`.`meta_value` = 'yes'
AND `wp_gf_entry_meta`.`entry_id` = employeeByDiagnosedMentalIllness.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY employeeByDiagnosedMentalIllness.`meta_value`


//***************************************************************************************

// Survey charts short codes

// Total number of submissions

SELECT count(*) AS 'numberSubmissions'
FROM `wp_gf_entry_meta`
WHERE `wp_gf_entry_meta`.`meta_key` = 'is_survey_entry_submitted_by_user' 
AND `wp_gf_entry_meta`.`meta_value` = 'yes'
AND `wp_gf_entry_meta`.`form_id` = 18

// OSIR average company score (Pie chart)

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
Count(*), 
SUM(osirScore.`meta_value`) AS 'Total OSIR score'
FROM `wp_gf_entry_meta`, 
(SELECT * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'total_osir_score') osirScore 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = osirScore.`entry_id`
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY osirProfile

// General mental outlook average company score

SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', 
Count(*), 
SUM(outlookScore.`meta_value`) AS 'totalOutlookScore',
AVG(outlookScore.`meta_value`) AS 'avgOutlookScore'
FROM `wp_gf_entry_meta`, 
(SELECT * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'total_outlook_score') outlookScore 
WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile' 
AND `wp_gf_entry_meta`.`entry_id` = outlookScore.`entry_id` 
AND `wp_gf_entry_meta`.`form_id` = 17
GROUP BY osirProfile

//
