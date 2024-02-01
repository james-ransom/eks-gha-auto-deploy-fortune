ok ok not doing ci i dont' want to pay aws lol 

# A simple DEMO of CI/CD using GHA and EKS 🚀

Commit code -> Github Actions -> Build Container Image -> push Amazon EKS -> Scale -> profit! 🔥🔥


# This is an example of: 

1) Github actions building the image after any commit and push

2) auto pushing to a eks cluster, with autoscaling enabled 

# Let's GO! 

You need these in your Github actions secrets: <br>
<img src='https://raw.githubusercontent.com/james-ransom/eks-gha-auto-deploy-fortune/main/images/keysyouneed.png' width='700px'>

# To get KUBECONFIG

```
aws eks --region us-east-1 update-kubeconfig --name fortune
mv ~/.kube/config  ~/.kube/bk_config
cat ~/.kube/config 
mv ~/.kube/bk_config  ~/.kube/config
```

# Setup your EKS cluster 
```
eksctl create cluster --name fortune --region us-east-1
aws eks --region us-east-1 update-kubeconfig --name fortune #connect to it

```

# Setup your nodegroup 
```
eksctl create nodegroup \
  --cluster fortune \
  --region us-east-1 \
  --name fortune-node \
  --node-type m5.small \
  --nodes 1 \
  --nodes-min 1 \
  --nodes-max 4 
```

# Setup your container registry and get your GCR_REPRO_URL
```
$ aws ecr create-repository --region us-east-1 --repository-name fortune
{
    "repository": {
        "repositoryArn": "arn:aws:ecr:us-east-1:XXXXXXXX:repository/fortune",
        "registryId": "XXXXXXXX",
        "repositoryName": "fortune",
        "repositoryUri": "XXXXXXXX.dkr.ecr.us-east-1.amazonaws.com/fortune",
        "createdAt": "2022-10-14T07:29:31-07:00",
        "imageTagMutability": "MUTABLE",
        "imageScanningConfiguration": {
            "scanOnPush": false
        },
        "encryptionConfiguration": {
            "encryptionType": "AES256"
        }
    }
}
```
In the above case your GCR_REPRO_URL is XXXXXXXX.dkr.ecr.us-east-1.amazonaws.com


# Let's BUILD! Any commit will trigger a build and push! 
<img src='https://raw.githubusercontent.com/james-ransom/eks-gha-auto-deploy-fortune/main/images/build.png' width='400px'>

# How can we see our pods/nodes/endpoints
```
aws eks --region us-east-1 update-kubeconfig --name fortune

$ kubectl get pods
NAME                           READY   STATUS             RESTARTS   AGE
backend-k8s-5b4c97b57b-7mkxr   0/1     Pending            0          33s
backend-k8s-5b4c97b57b-gbzq7   0/1     Pending            0          33s
backend-k8s-5b4c97b57b-km4qn   0/1     Pending            0          33s

```

```
$ kubectl get services
NAME          TYPE           CLUSTER-IP       EXTERNAL-IP                                                               PORT(S)        AGE
backend-k8s   LoadBalancer   10.100.253.226   XXXXXXX.us-east-1.elb.amazonaws.com   80:32270/TCP   15h
kubernetes    ClusterIP      10.100.0.1       <none>                                                                    443/TCP        43d
$ 
```

```
$ eksctl get nodegroup --cluster=fortune 

CLUSTER	NODEGROUP		STATUS	CREATED			MIN SIZE	MAX SIZE	DESIRED CAPACITY	INSTANCE TYPE	IMAGE ID	ASG NAME						TYPE
fortune	small-group-scaling	ACTIVE	2022-11-26T01:54:06Z	1		10		6			t2.small	AL2_x86_64	eks-small-group-scaling-XX	managed
```


# I want to scale! 🚀🚀🚀

```
kubect apply -f hpa.yaml #horizontal pod scaler 
```

# Download the cluster autoscaler: 

```
curl -o cluster-autoscaler-autodiscover.yaml https://raw.githubusercontent.com/kubernetes/autoscaler/master/cluster-autoscaler/cloudprovider/aws/examples/cluster-autoscaler-autodiscover.yaml
```

Modify the YAML file and replace <YOUR CLUSTER NAME> with your cluster name. Also consider replacing the cpu and memory values as determined by your environment.


```
kubect apply -f cluster-autoscaler-autodiscover.yaml #nodeautoscaler
```

# I want to see the scaling work! 🚀🚀🚀

View the pod auto scaler
```
$ kubectl get hpa
NAME          REFERENCE                TARGETS   MINPODS   MAXPODS   REPLICAS   AGE
backend-k8s   Deployment/backend-k8s   0%/60%    1         10000     2          2d2h
```

View the node autoscaler logs
```
kubectl -n kubectlube-system logs -f deployment.apps/cluster-autoscaler #note! you will have to update the cluster name from fortune, if you change it


I1127 06:04:19.162423       1 filter_out_schedulable.go:65] Filtering out schedulables
I1127 06:04:19.162436       1 filter_out_schedulable.go:132] Filtered out 0 pods using hints
I1127 06:04:19.162444       1 filter_out_schedulable.go:170] 0 pods were kept as unschedulable based on caching
I1127 06:04:19.162450       1 filter_out_schedulable.go:171] 0 pods marked as unschedulable can be scheduled.
I1127 06:04:19.162460       1 filter_out_schedulable.go:82] No schedulable pods
I1127 06:04:19.162477       1 static_autoscaler.go:420] No unschedulable pods
I1127 06:04:19.162491       1 static_autoscaler.go:467] Calculating unneeded nod
```
# Profit!
<img src='https://www.opendoctor.io/retina.svg?fromgithub=1' width='50px'>
