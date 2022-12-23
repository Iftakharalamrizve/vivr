import type { NextPage } from 'next'
import { AdminLayout } from '@layout'
import React from 'react'
import Tree from 'src/components/TreeView/Tree';
const treeData = [{}]

const TreeConfPage: NextPage = () => (
  <AdminLayout>
     <div id="div_tree">
        <Tree treeData={treeData} nodeLength={treeData.length -1} />
    </div>
  </AdminLayout>
)

export default TreeConfPage
