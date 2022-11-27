[![cd](https://github.com/james-ransom/eks-gha-auto-deploy-fortune/actions/workflows/cd.yml/badge.svg)](https://github.com/james-ransom/eks-gha-auto-deploy-fortune/actions/workflows/cd.yml)

# A simple! CI/CD

Commit code -> Github Actions -> Run tests -> push to Amazon EKS [Managed Kubernetes Service – Amazon EKS]


# This is an example of: 

1) Github actions building the image after any commit and push

2) auto pushing to a eks cluster, with autoscaling enabled 

# Let's GO! 

You need these: <br>
<img src='https://raw.githubusercontent.com/james-ransom/eks-gha-auto-deploy-fortune/main/images/keysyouneed.png' width='700px'>

To get KUBECONFIG

```
aws eks --region us-east-1 update-kubeconfig --name fortune
mv ~/.kube/config  ~/.kube/bk_config
cat ~/.kube/config 
mv ~/.kube/bk_config  ~/.kube/config
```


[force push v3]
